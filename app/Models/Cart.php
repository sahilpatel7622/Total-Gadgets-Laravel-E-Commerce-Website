<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'add_to_cart';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}