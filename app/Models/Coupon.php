<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'discount_value',
        'user_type',
        'minimum_order_amount',
        'usage_limit',
        'per_user_limit',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'coupon_users',
            'coupon_id',
            'user_id'
        );
    }
}