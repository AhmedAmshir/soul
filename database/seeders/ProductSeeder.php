<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $product = Product::create([
            'name' => 'Soul',
        ]);

        $variations = [
            [
                'slug' => 'lavender-120ml',
                'description' => 'Relaxing lavender scent with floral notes.',
                'smell' => 'Floral, Woody',
                'size_ml' => 120,
                'image' => 'lavender-50ml.jpg',
                'price' => 25.00,
                'stock' => 200
            ],
            [
                'slug' => 'lavender-100ml',
                'description' => 'Strong lavender scent with a hint of vanilla.',
                'smell' => 'Floral, Sweet',
                'size_ml' => 100,
                'image' => 'lavender-100ml.jpg',
                'price' => 45.00,
                'stock' => 250
            ],
            [
                'slug' => 'lavender-150ml',
                'description' => 'Intense lavender scent with a touch of rose.',
                'smell' => 'Floral, Vanilla',
                'size_ml' => 150,
                'image' => 'lavender-150ml.jpg',
                'price' => 65.00,
                'stock' => 150
            ],
            [
                'slug' => 'lavender-200ml',
                'description' => 'Strong lavender scent with a hint of vanilla.',
                'smell' => 'White Tea',
                'size_ml' => 200,
                'image' => 'lavender-200ml.jpg',
                'price' => 75.00,
                'stock' => 100
            ]
        ];

        foreach ($variations as $variation) {
            ProductVariation::create([
                'product_id' => 1,
                ...$variation,
            ]);
        }
    }
}
