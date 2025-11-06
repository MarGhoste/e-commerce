<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'stock', 'is_active'];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];


    public function category()
    {
        // Un producto pertenece a una categoría
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        // Un producto tiene muchas imágenes
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems()
    {
        // Un producto aparece en muchos ítems de órdenes
        return $this->hasMany(OrderItem::class);
    }
    public function mainImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    // --- ACCESSOR/GETTER (NUEVO) ---

    /**
     * Crea un atributo virtual llamado 'image_url' que Vue puede consumir.
     * Acceso: $product->image_url
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->mainImage) {
            return Storage::url($this->mainImage->url);
        }
        return null;
    }
}
