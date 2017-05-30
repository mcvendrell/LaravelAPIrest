<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
{
    /**
     * Queremos mostrar los productos de un comprador
     * El problema es que un comprador tiene varias transacciones y no es posible hacerlo de la manera habitual porque en
     * $buyer->transactions->products, transactions es la relación entre tablas y devuelve una colección y no un modelo, lo que generaría error
     * En su lugar tenemos que hacer que la relación se ejecute desde QueryBuilder para que nos devuelva todas las filas que contengan productos 
     * 
     * Esto se hace ejecutando la *función* y no la relación, añadiendo los paréntesis. 
     * Pero esto devuelve una collección con todas las transacciones y, dentro de cada transacción, otra colección con todos los productos
     * Como solo nos interesan los productos, usamos pluck, que permite quedarte solo con las colecciones internas (son arrays, dentro de la key "relations")
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $products = $buyer->transactions()->with('product')->get()->pluck('product');

        return $this->showAll($products);
    }
}
