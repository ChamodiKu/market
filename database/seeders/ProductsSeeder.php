<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Nette\Utils\Floats;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'product_name' => Str::random(20),
            'price' => Floats::compare(0,10000),
            'image' => Str::random(100),
            'stock' => Floats::compare(0,10000)
        ]);
    }
}
