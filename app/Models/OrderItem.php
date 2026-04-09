<?php

namespace App\Models;

use Database\Factories\OrderItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'order_id'   => 'integer',
            'product_id' => 'integer',
            'quantity'   => 'integer',
            'price'      => 'decimal:2', 
        ];
    }

    public function product()
    {
        // One order item belongs to one product
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
