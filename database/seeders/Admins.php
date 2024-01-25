<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use  App\Models\Admin;

class Admins extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
