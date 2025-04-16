<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'full_name',
        'email',
        'phone',
        'delivery_method',
        'payment_method',
        'status',
        'subtotal',
        'delivery_fee',
        'total',
        'notes',
        'street',
        'house_number',
        'city',
        'postal_code',
        'delivery_time',
        'pickup_time',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}