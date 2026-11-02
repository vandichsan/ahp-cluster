<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
            'name' => 'Manajer Synthesis Huis',
            'email' => 'manajer@synthesishuis.com',
            'password' => Hash::make('rahasia123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}