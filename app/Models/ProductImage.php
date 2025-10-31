<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'url',
        'is_main',
        // 'sort_order', // Si lo incluiste en la migración
    ];

    public function product()
    {
        // Una imagen de producto pertenece a un único producto
        return $this->belongsTo(Product::class);
    }
}
