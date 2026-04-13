<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_quantity',
        'total_price',
        'receiver_name',
        'receiver_phone',
        'receiver_address',
        'note',
        'status',
        'discount_id',
        'discount_code',
        'discount_value',
        'payment_method'
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'discount_value' => 'decimal:2',
        ];
    }

    public function items()
    {
        // One order has many order items, linked by order_id in the order_items table
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
