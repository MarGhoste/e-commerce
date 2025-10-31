<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Relación con orders
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null'); // El producto comprado
            $table->integer('quantity');
            $table->decimal('price', 8, 2); // Precio al momento de la compra (crucial para históricos)
            // Se recomienda guardar el nombre del producto aquí si product_id es nulo (si el producto se elimina)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
