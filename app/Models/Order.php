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
        'coupon_id',
        'coupon_code',
        'subtotal',
        'shipping',
        'discount',
        'total',
        'status',
        'payment_status',
        'transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'ship_to_different' => 'boolean',
        'shipping_address' => 'array',
        'paid_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
