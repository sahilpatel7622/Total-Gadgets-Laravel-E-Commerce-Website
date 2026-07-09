<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Payment extends Model
{
    protected $table =  'payments';
    protected $fillable = [
        'order_id',
        'user_id',
        'amount',
        'payment_method',
        'payment_status',
        'razorpay_payment_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
         return $this->belongsTo(User::class, 'user_id');
    }
    
    public function detail()
    {
        return $this->hasOne(OrderDetail::class, 'order_id', 'id');
    }
}