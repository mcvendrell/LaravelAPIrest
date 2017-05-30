<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Queremos mostrar los vendedores que ha tenido un comprador
     * El problema es que un comprador tiene varias transacciones, la transacción tiene un producto y este está asaciado a un vendedor
     * Esto nos puede dar vendedores repetidos. Recordar que en
     * $buyer->transactions->products, transactions es la relación entre tablas y devuelve una colección y no un modelo, lo que generaría error
     * En su lugar tenemos que hacer que la relación se ejecute desde QueryBuilder para que nos devuelva todas las filas que contengan productos 
     * 
     * Esto se hace ejecutando la *función* y no la relación, añadiendo los paréntesis. 
     * Pero esto devuelve una colección con todas las transacciones y, dentro de cada transacción, otra colección con todos los productos
     * Como solo nos interesan los productos, usamos pluck(), que permite quedarte solo con las colecciones internas (son arrays, dentro de la key "relations")
     * Como además, no queremos que se repitan vendedores, usamos unique(), escogiendo el id. El problema es que unique deja elementos vacíos en el array final
     * para evitar las repeticiones, así que añadimos la función values() que obtiene solo los elementos con valor en un nuevo array ordenado.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()->with('product.seller')
            ->get()
            ->pluck('product.seller')
            ->unique('id')
            ->values();

        return $this->showAll($sellers);
    }
}
