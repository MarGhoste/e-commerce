<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price', // Guardamos el precio para consistencia, incluso si el producto cambia
    ];

    /**
     * Un artículo de carrito pertenece a un carrito.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Un artículo de carrito pertenece a un producto.
     */
    public function product(): BelongsTo
    {
        // Usamos withDefault() para evitar errores si el producto fue eliminado
        return $this->belongsTo(Product::class)->withDefault();
    }
}
