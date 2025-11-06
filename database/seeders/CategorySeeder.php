<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            // Categorías existentes
            ['name' => 'Laptops', 'slug' => 'laptops', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Monitores', 'slug' => 'monitores', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Teclados', 'slug' => 'teclados', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Componentes', 'slug' => 'componentes', 'created_at' => now(), 'updated_at' => now()],


            ['name' => 'Gaming', 'slug' => 'gaming', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Almacenamiento', 'slug' => 'almacenamiento', 'created_at' => now(), 'updated_at' => now()], // 6 categorías para mayor variedad
        ]);
    }
}
