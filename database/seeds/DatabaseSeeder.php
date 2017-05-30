<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();
        
        factory(User::class, 200)->create();
        factory(Category::class, 10)->create();
        
        factory(Product::class, 500)->create()->each(
            function($product) {
                // Escoger aleatoriamente entre 1 y 5 categorías para asociar a cada producto. Solo necesitamos el id, no todo el objeto
                $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
                
                $product->categories()->attach($categories);
            }
        );
        
        factory(Transaction::class, 200)->create();
    }
}
