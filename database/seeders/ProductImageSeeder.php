<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $images = [];

        foreach ($products as $product) {
            // Generar una imagen principal (is_main = true)
            $mainImageUrl = 'https://placehold.co/400x300' . rand(1, 100) . '/400/300';

            $images[] = [
                'product_id' => $product->id,
                'url' => $mainImageUrl,
                'is_main' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Generar 2 imágenes adicionales (is_main = false) para simular galería
            for ($i = 0; $i < 2; $i++) {
                $secondaryImageUrl = 'https://placehold.co/400x300' . rand(101, 200) . '/400/300';
                $images[] = [
                    'product_id' => $product->id,
                    'url' => $secondaryImageUrl,
                    'is_main' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        ProductImage::insert($images);
    }
}
