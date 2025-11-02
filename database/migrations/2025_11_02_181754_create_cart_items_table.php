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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            // Clave foránea al carrito
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            // Clave foránea al producto
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->unsignedInteger('quantity')->default(1);

            // Precio unitario al momento de añadir (para registro)
            $table->decimal('price', 8, 2);

            // Asegura que no haya duplicados del mismo producto en el mismo carrito
            $table->unique(['cart_id', 'product_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
