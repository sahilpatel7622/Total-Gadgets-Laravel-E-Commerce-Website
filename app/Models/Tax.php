<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $table = 'tax';

    protected $fillable = [
        'tax_percentage',
        'delivery_charge',
        'free_delivery_above',
    ];

    protected $casts = [
        'tax_percentage' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'free_delivery_above' => 'decimal:2',
    ];
}