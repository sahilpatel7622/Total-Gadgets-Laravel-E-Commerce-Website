<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class category extends Model
{
    use SoftDeletes;

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
