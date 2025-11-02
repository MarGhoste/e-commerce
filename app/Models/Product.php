<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'stock', 'is_active'];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

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
}
