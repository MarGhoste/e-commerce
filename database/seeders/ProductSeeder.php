<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener IDs de todas las categorías
        $categories = Category::pluck('id', 'slug')->toArray();

        // Estructura de productos (Nombre, Descripción, Precio, Stock)
        $productsData = [
            'laptops' => [
                ['Laptop Profesional Dell XPS 15', 'Pantalla OLED 4K, i9, 32GB RAM. Máximo rendimiento.', 2599.99, 10],
                ['MacBook Air M2', 'Ultraportátil, rendimiento superior con chip M2.', 1199.00, 20],
                ['Chromebook Económica', 'Ideal para tareas básicas y estudiantes. Batería de larga duración.', 350.50, 40],
                ['Laptop HP Pavilion', 'Equilibrio perfecto entre precio y rendimiento.', 950.00, 25],
                ['Portátil Lenovo ThinkPad', 'Construcción robusta y teclado ergonómico, para negocios.', 1400.00, 15],
            ],
            'monitores' => [
                ['Monitor Curvo 34" Ultrawide', 'Resolución WQHD, 144Hz, inmersión total.', 499.99, 18],
                ['Monitor 27" 4K Profesional', 'Colores precisos y calibración de fábrica, para diseño.', 720.00, 12],
                ['Monitor Básico 24" FHD', 'Perfecto para oficina y tareas diarias.', 150.99, 50],
                ['Monitor Gaming 240Hz', 'Tiempo de respuesta 1ms, ideal para eSports.', 380.50, 22],
                ['Monitor Táctil 15"', 'Para puntos de venta o interacción directa.', 250.00, 10],
            ],
            'teclados' => [
                ['Teclado Mecánico Red Switch', 'Rápido y silencioso, ideal para programar y escribir.', 95.00, 60],
                ['Teclado Inalámbrico Ergonómico', 'Diseño dividido para máxima comodidad en largas jornadas.', 85.99, 35],
                ['Teclado Gaming 60%', 'Compacto, sin teclado numérico, para ahorrar espacio.', 65.00, 70],
                ['Teclado y Ratón Combo Oficina', 'Solución económica y funcional.', 35.50, 100],
                ['Teclado RGB con Display', 'Personalizable con pantalla OLED para información rápida.', 130.00, 15],
            ],
            'componentes' => [
                ['Tarjeta Gráfica RTX 4080', 'Potencia extrema para 4K y Ray Tracing.', 1199.99, 8],
                ['Procesador Intel Core i7 14ª Gen', 'Alto rendimiento en multi-núcleo.', 420.00, 18],
                ['Memoria RAM 32GB DDR5', 'Kit dual channel de alta velocidad.', 150.00, 40],
                ['Fuente de Poder 850W Platinum', 'Eficiencia máxima y modularidad.', 135.50, 25],
                ['Placa Madre Z790 Chipset', 'Soporte para CPU y RAM de última generación.', 280.00, 12],
            ],
            'gaming' => [
                ['Silla Gamer Ergonómica XL', 'Soporte lumbar y cervical, acabado en piel sintética.', 299.00, 15],
                ['Mouse Gaming Inalámbrico 26K DPI', 'Precisión extrema y carga rápida.', 99.50, 30],
                ['Headset Gaming 7.1 Surround', 'Micrófono con cancelación de ruido.', 85.00, 40],
                ['Alfombrilla XXL RGB', 'Base antideslizante y bordes iluminados.', 35.00, 60],
                ['Volante de Carreras Force Feedback', 'Para una inmersión total en simuladores.', 450.00, 10],
            ],
            'almacenamiento' => [
                ['SSD NVMe 2TB Gen4', 'Velocidad de lectura/escritura ultra rápida.', 149.99, 30],
                ['Disco Duro Externo 8TB USB 3.0', 'Gran capacidad para copias de seguridad.', 180.00, 20],
                ['Pendrive USB 3.2 256GB', 'Alta velocidad para transferencias diarias.', 45.50, 50],
                ['Tarjeta MicroSD 512GB Clase 10', 'Para cámaras y móviles.', 60.00, 70],
                ['SSD Sata 1TB Económico', 'Mejora el rendimiento de un PC antiguo.', 75.00, 45],
            ],
        ];

        $products = [];
        foreach ($productsData as $slug => $list) {
            $category_id = $categories[$slug] ?? null;

            if ($category_id) {
                foreach ($list as $item) {
                    list($name, $description, $price, $stock) = $item;
                    $products[] = [
                        'category_id' => $category_id,
                        'name' => $name,
                        'slug' => str()->slug($name), // Crea el slug automáticamente
                        'description' => $description,
                        'price' => $price,
                        'stock' => $stock,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        DB::table('products')->insert($products);
    }
}
