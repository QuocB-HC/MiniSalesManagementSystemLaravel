<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    public function items()
    {
        // One order has many order items, linked by order_id in the order_items table
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
