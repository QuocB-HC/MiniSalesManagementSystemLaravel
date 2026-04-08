<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        // Take current cart from session or initialize if not exists
        $cart = session()->get('cart', []);

        // If product already in cart, increase quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // If not exists, add new item to the array
            $cart[$id] = [
                'name' => $product->name,
                'quantity' => 1,
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

        return redirect()->back()->with('success', 'Đã cập nhật số lượng!');
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

    public function placeOrder(Request $request)
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

            // 4. Save to 'orders' table
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_quantity' => $totalQuantity,
                'total_price' => $totalPrice,
                'receiver_name' => $request->name,
                'receiver_phone' => $request->phone,
                'receiver_address' => $request->address,
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

            // 6. Delete the cart after successful order placement
            session()->forget('cart');

            // Confirm saving everything to the database
            DB::commit();

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            // If any error occurs, rollback the transaction to prevent database clutter
            DB::rollBack();

            return redirect()->back()->with('error', 'System Error: '.$e->getMessage());
        }
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
