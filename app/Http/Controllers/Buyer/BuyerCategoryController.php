<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    /**
     * Queremos mostrar las categorías en las que un comprador ha comprado algo
     * El problema es que un comprador tiene varias transacciones, la transacción tiene un producto y este está asaciado a una categoría (de una lista de ellas)
     * Esto nos puede dar categorías repetidos. Recordar que en
     * $buyer->transactions->products, transactions es la relación entre tablas y devuelve una colección y no un modelo, lo que generaría error
     * En su lugar tenemos que hacer que la relación se ejecute desde QueryBuilder para que nos devuelva todas las filas que contengan productos 
     * 
     * Esto se hace ejecutando la *función* y no la relación, añadiendo los paréntesis. 
     * Pero esto devuelve una colección con todas las transacciones y, dentro de cada transacción, otra colección con todos los productos
     * Como solo nos interesan las categorías de los productos, usamos pluck(), que permite quedarte solo con las colecciones internas (son arrays, dentro de la key "relations")
     * Adicionalmente a esto, en el caso de categorías lo que obtenemos son nuevamente listas (a diferencia de los vendedores, que obteníamos directamente el vendedor)
     * porque un producto puede pertenecer a varias categorías. Así que, al final, estamos obteniendo un array compuesto por por varios elementos que a su vez
     * son otro array (las categorías de cada producto). Esto se resuelve usando la función collapse(), que unifica varios arrays en uno solo.
     * Como además, no queremos que se repitan categorías, usamos unique(), escogiendo el id. El problema es que unique deja elementos vacíos en el array final
     * para evitar las repeticiones, así que añadimos la función values() que obtiene solo los elementos con valor en un nuevo array ordenado.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
            ->get()
            ->pluck('product.categories')
            ->collapse()
            ->unique('id')
            ->values();

        return $this->showAll($categories);
    }
}
