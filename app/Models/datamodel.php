<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class datamodel extends Model
{

    protected $table = 'data';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'number',
        'address',
        'gender',
        'image',
    ];
    public function locationMapping()
    {        
        return $this->hasOne(location_mapping::class, 'data_id', 'id');
    }
}
