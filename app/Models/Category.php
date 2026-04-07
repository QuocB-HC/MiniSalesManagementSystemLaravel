<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug']; // Add 'slug' to the fillable properties

    // Relationship: Một category có nhiều products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
