<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'status',
    ];

    /**
     * Un carrito pertenece a un usuario (puede ser nulo si es invitado).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un carrito tiene múltiples artículos (CartItem).
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Calcula el subtotal del carrito.
     */
    public function getSubtotalAttribute()
    {
        return $this->items->sum(function ($item) {
            // Calcula (precio al momento de añadir * cantidad)
            return $item->price * $item->quantity;
        });
    }
}
