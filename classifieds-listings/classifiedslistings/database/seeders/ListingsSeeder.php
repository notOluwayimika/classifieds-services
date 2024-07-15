<?php

namespace Database\Seeders;

use App\Models\Listings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ListingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Listings::factory()->count(10)->create();
    }
}
