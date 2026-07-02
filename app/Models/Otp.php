<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'otps';

    protected $fillable = [
        'user_id',
        'email',
        'otp',
        'type',
        'expiry',
    ];

    protected $casts = [
        'expiry' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}