<?php

namespace App\Http\Controllers\Api;

use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->get();

        return response()->json(['status' => 'success', 'data' => $orders]);
    }

    public function show($id)
    {
        // Get Order with Items and Product information of each Item
        $order = Order::with('items.product')->find($id);

        if (! $order || $order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Order not found or Unauthorized'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validate data
        $request->validate([
            'receiver_name' => 'required|string',
            'receiver_phone' => 'required',
            'receiver_address' => 'required',
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'discount_code' => 'nullable|string|exists:discounts,code',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $totalPrice = 0;
                $discountValue = 0;
                $discountId = null;
                $appliedCode = null;
                $totalQuantity = 0;
                $orderItemsData = []; // Temp array to save items

                // 2. Check item and calculate total price
                foreach ($request->cart as $item) {
                    $product = Product::lockForUpdate()->find($item['id']);

                    if ($product->stock_quantity < $item['quantity']) {
                        // Return error instead of Exception
                        throw new \Exception("Product {$product->name} out of stock.");
                    }

                    $itemPrice = $product->price * $item['quantity'];
                    $totalPrice += $itemPrice;
                    $totalQuantity += $item['quantity'];

                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $product->decrement('stock_quantity', $item['quantity']);
                }

                // 3. Handle discount
                if ($request->discount_code) {
                    $discount = Discount::where('code', $request->discount_code)
                        ->where('is_active', true)
                        ->where(function ($q) {
                            $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                        })->first();

                    if (! $discount) {
                        throw new \Exception('The discount code is invalid or has expired.');
                    }

                    if ($totalPrice < $discount->min_order_value) {
                        throw new \Exception('Order value must be at least'.number_format($discount->min_order_value).' VND to use this discount.');
                    }

                    $discountId = $discount->id;
                    $appliedCode = $discount->code;

                    if ($discount->type === DiscountType::PERCENTAGE) {
                        $discountValue = ($totalPrice * $discount->value) / 100;
                    } else {
                        $discountValue = $discount->value;
                    }

                    $discount->increment('used_count');
                }

                $finalPrice = max(0, $totalPrice - $discountValue);

                // 4. Create Order
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'discount_id' => $discountId,
                    'discount_code' => $appliedCode,
                    'discount_value' => $discountValue,
                    'total_quantity' => $totalQuantity,
                    'total_price' => $finalPrice,
                    'receiver_name' => $request->receiver_name,
                    'receiver_phone' => $request->receiver_phone,
                    'receiver_address' => $request->receiver_address,
                    'note' => $request->note,
                    'status' => OrderStatus::PENDING,
                ]);

                // 5. Save Order Items (Insert into array to optimize speed)
                foreach ($orderItemsData as &$itemData) {
                    $itemData['order_id'] = $order->id;
                }
                OrderItem::insert($orderItemsData);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Order created successfully!',
                    'order_id' => $order->id,
                ], 201);
            });
        } catch (\Exception $e) {
            // Return JSON instead of system
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipping,completed,cancelled',
        ]);

        $order = Order::with('items')->find($id);

        if (! $order) {
            return response()->json(['status' => 'fail', 'message' => 'Cannot find order'], 404);
        }

        // Check if the new state is the same as the old state; if so, no further action is needed
        if ($order->status->value === $request->status) {
            return response()->json(['message' => 'Status is the same as before'], 200);
        }

        return DB::transaction(function () use ($request, $order) {
            // IMPORTANT LOGIC: If you switch to Cancelled from an uncancelled state
            if ($request->status === OrderStatus::CANCELLED->value && $order->status !== OrderStatus::CANCELLED) {
                foreach ($order->items as $item) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }

            // If an order is currently canceled and the admin accidentally clicks the wrong button, it can be successfully returned
            // (Rarely but should consider whether or not to deduct it from storage)

            $order->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Update order status #'.$order->id.' successfully!',
                'data' => $order,
            ]);
        });
    }
}
