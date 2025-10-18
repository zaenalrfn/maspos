<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                "category_id" => 1,
                "name" => "Pizza Margherita",
                "price" => 100000,
                "image" => "assets/images/pizza_1.png",
            ],
            [
                "category_id" => 1,
                "name" => "Pizza Pepperoni",
                "price" => 120000,
                "image" => "assets/images/pizza_2.png",
            ],
            [
                "category_id" => 1,
                "name" => "Pizza Sausage",
                "price" => 130000,
                "image" => "assets/images/pizza_3.png",
            ],
            [
                "category_id" => 2,
                "name" => "Burger Classic",
                "price" => 80000,
                "image" => "assets/images/burger_1.png",
            ],
            [
                "category_id" => 2,
                "name" => "Burger Cheese",
                "price" => 90000,
                "image" => "assets/images/burger_2.png",
            ],
            [
                "category_id" => 4,
                "name" => "Coca Cola",
                "price" => 50000,
                "image" => "assets/images/coca_1.png",
            ],
            [
                'category_id' => 4,
                "name" => "Es Teh",
                "price" => 30000,
                "image" => "assets/images/teh_1.png",
            ]
        ];

        foreach ($products as $product) {
            Product::updateOrCreate($product);
        }
    }
}
