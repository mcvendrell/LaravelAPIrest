<?php

use App\Category;
use App\Product;
use App\Seller;
use App\Transaction;
use App\User;
use Faker\Generator;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'verified' => $verified = $faker->randomElement([User::USER_VERIFIED, User::USER_NOT_VERIFIED]),
        'verification_token' => $verified == User::USER_VERIFIED ? null : User::generateVerificationToken(),
        'admin' => $faker->randomElement([User::USER_ADMIN, User::USER_NORMAL]),
    ];
});

$factory->define(Category::class, function (Generator $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
    ];
});

$factory->define(Product::class, function (Generator $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement([Product::AVAILABLE, Product::NOT_AVAILABLE]),
        'image' => $faker->randomElement(['1.jpg','2.jpg','3.jpg']),
        //'seller_id' => User::inRandomOrder()->first()->id,
        'seller_id' => User::all()->random()->id,
    ];
});

$factory->define(Transaction::class, function (Generator $faker) {
    // Recordar que un vendedor no se puede vender a sí mismo, así que escoger uno cualquiera con productos a la venta
    // y obtener un comprador cualquiera que no sea este vendedor
    $seller = Seller::has('products')->get()->random();
    $buyer = User::all()->except($seller->id)->random();
    
    // Escogeremos un producto de un vendedor que ya sabemos que no es el comprador asignado
    return [
        'quantity' => $faker->numberBetween(1, 4),
        'buyer_id' => $buyer->id,
        'product_id' => $seller->products->random()->id,
    ];
});
