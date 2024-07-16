<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    protected static ?string $password;
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'test@admin.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role'=>'admin',
            'remember_token' => Str::random(10),
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Shop User',
            'email' => 'test@shop.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role'=>'shop',
            'remember_token' => Str::random(10),
        ]);
    }
}
