<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address1',
        'address2',
        'country',
        'city',
        'state',
        'zip',
        'ship_to_different',
        'shipping_address',
        'payment_method',
        'subtotal',
        'shipping',
        'total',
        'status',
    ];

    protected $casts = [
        'ship_to_different' => 'boolean',
        'shipping_address' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
