<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'name' => "Test Product",
            'description' => "Test Product Description",
            'stock' => 5,
            'price' => 10.25,
            'image' => "",
            'sku' => Str::random(10),
            'new_arrival' => 0,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        DB::table('products')->insert([
            'name' => "Test Product 2",
            'description' => "Test Product Description 2",
            'stock' => 50,
            'price' => 15.00,
            'image' => "",
            'sku' => Str::random(10),
            'new_arrival' => 1,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
    }
}
