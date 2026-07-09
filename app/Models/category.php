<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $table =  'category';
    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    public function product()
    {
        return $this->hasMany(product::class, 'c_id', 'id');
    }

}
