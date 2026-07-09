<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{   
    public function run(): void
    {
        User::create([
            'name'     => 'Sahil',
            'number'   => '1231231230',
            'email'    => 'sahil@gmail.com',
            'password' => Hash::make('123456'),
            'role'     => 'admin',
        ]);
    }
}