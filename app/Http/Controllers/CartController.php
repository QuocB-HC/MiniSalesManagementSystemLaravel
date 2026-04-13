<?php

namespace App\Http\Controllers;

use App\Mail\OrderNotification;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\VNPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $totalAmount = 0;

        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        return view('pages.cart', compact('cartItems', 'totalAmount'));
    }

    public function addToCart($id, Request $request)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:'.$product->stock_quantity,
        ]);

        $quantity = (int) $request->input('quantity', 1);
        // Take current cart from session or initialize if not exists
        $cart = session()->get('cart', []);

        // If product already in cart, increase quantity
        if (isset($cart[$id])) {
            // Check max quanity
            $newQuantity = $cart[$id]['quantity'] + $quantity;

            if ($newQuantity > $product->stock_quantity) {
                return redirect()->back()->with('error', 'Quantity exceeds stock!');
            }

            $cart[$id]['quantity'] = $newQuantity;
        } else {
            // If not exists, add new item to the array
            $cart[$id] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->image_url, // Ensure the column name matches your DB
            ];
        }

        // Save the updated cart to the session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    public function updateQuantity(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($request->action == 'increase') {
                $cart[$id]['quantity']++;
            } elseif ($request->action == 'decrease' && $cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } elseif ($request->action == 'decrease' && $cart[$id]['quantity'] == 1) {
                unset($cart[$id]); // Remove item if quantity is 1 and user tries to decrease
            }

            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Quantity updated!');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function checkout()
    {
        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $user = auth()->user();
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        return view('pages.payment', compact('cartItems', 'totalAmount', 'user'));
    }

    public function applyDiscount(Request $request)
    {
        $code = $request->input('code');
        $subtotal = $request->input('subtotal');

        $discount = Discount::where('code', $code)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (! $discount) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn.']);
        }

        if ($discount->usage_limit !== null && $discount->used_count >= $discount->usage_limit) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá này đã hết lượt sử dụng.']);
        }

        if ($subtotal < $discount->min_order_value) {
            return response()->json(['success' => false, 'message' => 'Đơn hàng tối thiểu '.number_format($discount->min_order_value, 0, ',', '.').' VNĐ để sử dụng mã này.']);
        }

        $discountAmount = 0;
        if ($discount->type === 'fixed') {
            $discountAmount = (float) $discount->value;
        } else {
            $discountAmount = ($subtotal * (float) $discount->value) / 100;
            if ($discount->max_discount_amount !== null && $discountAmount > $discount->max_discount_amount) {
                $discountAmount = (float) $discount->max_discount_amount;
            }
        }

        return response()->json([
            'success' => true,
            'discount_amount' => $discountAmount,
            'discount_id' => $discount->id,
            'message' => 'Áp dụng mã giảm giá thành công!',
        ]);
    }

    public function placeOrder(Request $request, VNPayService $vnpayService)
    {
        // 1. Take cart from Session
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // 2. Validate input data (receiver's info)
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'discount_id' => 'nullable|exists:discounts,id',
            'payment_method' => 'required|in:cod,vnpay',
        ]);

        // 3. Use Database Transaction to ensure safety
        // If saving product fails, order information will also be canceled (no database clutter)
        DB::beginTransaction();

        try {
            $totalPrice = 0;
            $totalQuantity = 0;

            foreach ($cart as $details) {
                $totalPrice += $details['price'] * $details['quantity'];
                $totalQuantity += $details['quantity'];
            }

            $appliedDiscountId = null;
            $appliedDiscountCode = null;
            $appliedDiscountValue = 0;

            // Check if there's a discount code applied and validate it again before saving to the database
            if ($request->filled('discount_id')) {
                $discount = Discount::find($request->discount_id);

                if ($discount && $discount->is_active &&
                    ($discount->expires_at == null || $discount->expires_at > now()) &&
                    ($totalPrice >= $discount->min_order_value)) {

                    $discountAmount = 0;
                    if ($discount->type === 'fixed') {
                        $discountAmount = (float) $discount->value;
                    } else {
                        $discountAmount = ($totalPrice * (float) $discount->value) / 100;
                        if ($discount->max_discount_amount !== null && $discountAmount > $discount->max_discount_amount) {
                            $discountAmount = (float) $discount->max_discount_amount;
                        }
                    }

                    $appliedDiscountId = $discount->id;
                    $appliedDiscountCode = $discount->code;
                    $appliedDiscountValue = $discountAmount;
                    $totalPrice = max(0, $totalPrice - $discountAmount);
                    $discount->increment('used_count');
                }
            }

            // 4. Save to 'orders' table
            $order = Order::create([
                'user_id' => Auth::id(),
                'discount_id' => $appliedDiscountId,
                'discount_code' => $appliedDiscountCode,
                'discount_value' => $appliedDiscountValue,
                'total_quantity' => $totalQuantity,
                'total_price' => $totalPrice, // Giá đã trừ discount
                'receiver_name' => $request->name,
                'receiver_phone' => $request->phone,
                'receiver_address' => $request->address,
                'note' => $request->note,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);

            // 5. Save to 'order_items' table (Details of each product)
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
            }

            // 6. PAYMENT STREAMING
            if ($request->payment_method == 'vnpay') {
                // VNPAY CASE:
                // - Do not deduct from storage immediately.
                // - Commit to save order int DB and go to pay.
                DB::commit();

                $paymentUrl = $vnpayService->createVnpayPayment($order);

                return redirect()->away($paymentUrl);
            } else {
                // COD CASE:
                // Update Order Status
                $order->update(['status' => 'processing']);

                // - Deduct from storage immediately.
                foreach ($cart as $id => $details) {
                    Product::where('id', $id)->decrement('stock_quantity', $details['quantity']);
                }

                session()->forget('cart');
                DB::commit();

                // Send mail to confirm for COD payment
                try {
                    Mail::to(Auth::user()->email)->send(new OrderNotification($order));
                } catch (\Exception $e) {
                    \Log::error('Mail error: '.$e->getMessage());
                }

                return redirect()->route('checkout.success', $order->id)
                    ->with('success', 'Order placed successfully!');
            }
        } catch (\Exception $e) {
            // If any error occurs, rollback the transaction to prevent database clutter
            DB::rollBack();

            return redirect()->back()->with('error', 'System Error: '.$e->getMessage());
        }
    }

    public function vnpayReturn(Request $request, VNPayService $vnpayService)
    {
        return $vnpayService->vnpayReturn($request);
    }

    public function orderSuccess($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        // Check if the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('pages.order-success', compact('order'));
    }
}
