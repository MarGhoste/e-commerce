<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category; // Asegúrate de importar tu modelo Category

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de categorías
        $laptopId = Category::where('slug', 'laptops')->value('id');
        $tecladoId = Category::where('slug', 'teclados')->value('id');

        DB::table('products')->insert([
            // Producto 1: Laptop
            [
                'category_id' => $laptopId,
                'name' => 'Laptop Gamer RTX 4070',
                'slug' => 'laptop-gamer-rtx-4070',
                'description' => 'Potente laptop con gráficos de última generación, ideal para gaming y desarrollo.',
                'price' => 1899.99,
                'stock' => 15,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Producto 2: Laptop
            [
                'category_id' => $laptopId,
                'name' => 'Portátil Ultrabook i5',
                'slug' => 'portatil-ultrabook-i5',
                'description' => 'Diseño delgado y ligero con procesador Intel Core i5. Perfecta para la universidad.',
                'price' => 850.00,
                'stock' => 30,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Producto 3: Teclado
            [
                'category_id' => $tecladoId,
                'name' => 'Teclado Mecánico RGB Pro',
                'slug' => 'teclado-mecanico-rgb-pro',
                'description' => 'Switches táctiles, iluminación personalizable y reposamuñecas ergonómico.',
                'price' => 120.50,
                'stock' => 50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Puedes añadir más productos aquí...
        ]);
    }
}
