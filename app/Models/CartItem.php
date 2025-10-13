<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model {
    
    protected $fillable = ['cart_id', 'product_variation_id', 'quantity'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }
}
