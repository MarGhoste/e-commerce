<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'shipping_address_line_1',
        'shipping_city',
        'shipping_zip',
        'payment_method',
    ];

    public function user()
    {
        // Una orden pertenece a un usuario (cliente)
        // La relación es opcional (nullable) para compras de invitados
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        // Una orden tiene muchos ítems (productos comprados)
        return $this->hasMany(OrderItem::class);
    }
}
