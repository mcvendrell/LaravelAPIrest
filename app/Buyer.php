<?php

namespace App;

use App\Scopes\BuyerScope;
use App\Transaction;

class Buyer extends User
{
	// Vamos a forzar el uso del Scope que hemos creado para los Buyers
	protected static function boot() {
		parent::boot();

		// Se usa el operador "static" por estar dentro de un método "static"
		static::addGlobalScope(new BuyerScope);
	}

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
