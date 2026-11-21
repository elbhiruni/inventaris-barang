<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::factory()->createMany([
            [
                'name' => 'AMD',
                'description' => null,
            ],
            [
                'name' => 'Asus',
                'description' => null,
            ],
            [
                'name' => 'Corsair',
                'description' => null,
            ],
            [
                'name' => 'G.Skill',
                'description' => null,
            ],
            [
                'name' => 'Gigabyte',
                'description' => null,
            ],
            [
                'name' => 'Intel',
                'description' => null,
            ],
            [
                'name' => 'Lenovo',
                'description' => null,
            ],
            [
                'name' => 'Logitech',
                'description' => null,
            ],
            [
                'name' => 'MSI',
                'description' => null,
            ],
        ]);
    }
}
