<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BuyerScope implements Scope
{
	// Para el modelo Buyer, queremos especificar que cada vez que se ejecute una consulta sobre él, 
	// se incluya la condición de que debe tener transacciones, ya que sino no es un Buyer
	public function apply(Builder $builder, Model $model) {
		$builder->has('transactions');
	}
}