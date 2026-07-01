<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class location_mapping extends Model
{
    protected $table = 'user_location_mapping';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'data_id',
        'countries_id',
        'states_id',
        'cities_id',
    ];  

    public function country()
    {
        return $this->belongsTo(Country::class, 'countries_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'states_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'cities_id', 'id');
    }
}