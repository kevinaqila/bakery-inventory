<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'description',
        'category_id',
        'unit',
        'purchase_price',
        'selling_price',
        'stock_quantity',
        'reorder_level',
        'is_active'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
