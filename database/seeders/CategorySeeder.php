<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()->createMany([
            [
                'name' => 'Case',
                'description' => 'PC Case',
            ],
            [
                'name' => 'Case Fan',
                'description' => 'PC Case Fan',
            ],
            [
                'name' => 'CPU',
                'description' => 'Processor',
            ],
            [
                'name' => 'CPU Cooler',
                'description' => 'Fan and Heatsink for CPU',
            ],
            [
                'name' => 'Graphics Card',
                'description' => null,
            ],
            [
                'name' => 'Keyboard',
                'description' => null,
            ],
            [
                'name' => 'Monitor',
                'description' => null,
            ],
            [
                'name' => 'Motherboard',
                'description' => null,
            ],
            [
                'name' => 'Mouse',
                'description' => null,
            ],
            [
                'name' => 'Power Supply Unit',
                'description' => null,
            ],
            [
                'name' => 'RAM',
                'description' => null,
            ],
            [
                'name' => 'Storage',
                'description' => null,
            ],
        ]);
    }
}
