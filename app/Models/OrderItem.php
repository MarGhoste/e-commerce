<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    public function order()
    {
        // Un ítem de orden pertenece a una orden
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        // Un ítem de orden pertenece a un producto
        // Es nullable porque el producto podría ser eliminado después de la compra
        return $this->belongsTo(Product::class)->withDefault([
            'name' => 'Producto Eliminado',
        ]);
    }
}
