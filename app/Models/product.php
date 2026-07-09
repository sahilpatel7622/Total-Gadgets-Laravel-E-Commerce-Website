<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $table =  'product';
    protected $fillable = [
        'c_id',
        'name',
        'slug',
        'price',
        'image',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(category::class, 'c_id', 'id');    
    }

}
