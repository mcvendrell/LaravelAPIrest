<?php

namespace App;

use App\Product;
use App\Scopes\SellerScope;

class Seller extends User
{
	// Vamos a forzar el uso del Scope que hemos creado para los Sellers
	protected static function boot() {
		parent::boot();

		// Se usa el operador "static" por estar dentro de un método "static"
		static::addGlobalScope(new SellerScope);
	}

    public function products() {
        return $this->hasMany(Product::class);
    }
}
