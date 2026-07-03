<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceModel extends Model
{
    use HasFactory;

    protected $table = 'Maintenance_Mode';

    protected $fillable = [
        'maintenance_mode',
    ];
}