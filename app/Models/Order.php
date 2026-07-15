<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Payment;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'amount',
        'address',
        'status',

        'coupon_id',
        'coupon_code',
        'coupon_discount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }
    
    public function detail()
    {
        return $this->hasOne(OrderDetail::class, 'order_id', 'id');
    }
}