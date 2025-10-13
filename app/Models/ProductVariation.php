<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model {

    protected $fillable = [
        'product_id', 'isin', 'slug', 'description', 'smell', 'size_ml', 'image', 'price', 'stock'
    ];

    public function product() {

        return $this->belongsTo(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
