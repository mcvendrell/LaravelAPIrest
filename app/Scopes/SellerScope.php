<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SellerScope implements Scope
{
	// Para el modelo Seller, queremos especificar que cada vez que se ejecute una consulta sobre él, 
	// se incluya la condición de que debe tener productos, ya que sino no es un Seller
	public function apply(Builder $builder, Model $model) {
		$builder->has('products');
	}
}