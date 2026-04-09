<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'sku',
    'category_id',
    'description',
    'price',
    'stock_quantity',
    'committed_quantity',
    'image_url',
    'status',
    'is_disabled',
    ])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'description',
        'price',
        'stock_quantity',
        'committed_quantity',
        'image_url',
        'status',
        'is_disabled',
    ];

    protected function cast(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'committed_quantity' => 'integer',
            'is_disabled' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
