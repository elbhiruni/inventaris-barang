<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = Brand::query()->pluck('id', 'name');
        $categories = Category::query()->pluck('id', 'name');

        Product::factory()->createMany([
            [
                'brand_id' => $brands->get('AMD'),
                'category_id' => $categories->get('CPU'),
                'name' => 'AMD Ryzen™ 5 9600X',
                'price' => 4350000,
                'stock_quantity' => 10,
            ],
            [
                'brand_id' => $brands->get('AMD'),
                'category_id' => $categories->get('CPU'),
                'name' => 'AMD Ryzen™ 7 9850X3D',
                'price' => 9639000,
                'stock_quantity' => 10,
            ],
            [
                'brand_id' => $brands->get('AMD'),
                'category_id' => $categories->get('CPU'),
                'name' => 'AMD Ryzen™ 9 9950X3D',
                'price' => 13515000,
                'stock_quantity' => 10,
            ],
        ]);
    }
}
