<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug']; // Campos que se pueden asignar masivamente

    public function products()
    {
        // Una categoría tiene muchos productos
        return $this->hasMany(Product::class);
    }
}
