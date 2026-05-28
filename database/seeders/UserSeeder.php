<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->create([
            'name' => 'Owner Aqlaya',
            'email' => 'owner@aqlaya.test',
            'phone' => '081200000000',
            'address' => 'Jl. Raya Aqlaya No. 1, Bandung',
            'role' => 'owner',
            'api_token' => Str::random(60),
            'password' => Hash::make('password'),
        ]);

        User::query()->create([
            'name' => 'Admin Aqlaya',
            'email' => 'admin@aqlaya.test',
            'phone' => '081200000001',
            'address' => 'Jl. Raya Aqlaya No. 17, Bandung',
            'role' => 'admin',
            'api_token' => Str::random(60),
            'password' => Hash::make('password'),
        ]);

        User::query()->create([
            'name' => 'Aya Customer',
            'email' => 'customer@aqlaya.test',
            'phone' => '081200000002',
            'address' => 'Jl. Melati No. 20, Bandung',
            'role' => 'customer',
            'api_token' => Str::random(60),
            'password' => Hash::make('password'),
        ]);
    }
}
