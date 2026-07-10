<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class product extends Model
{
    use SoftDeletes;

    protected $table =  'product';
    protected $fillable = [
        'c_id',
        'name',
        'slug',
        'price',
        'image',
        'description',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(category::class, 'c_id', 'id')->withTrashed();    
    }

}
