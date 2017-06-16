<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Queremos mostrar las transacciones realizadas para una categoría
     * El problema es que una categoría tiene varios productos, y estos están asaciados a una transacción ... o puede que no, si nunca han sido vendidos
     * Esto nos puede dar transacciones vacías y no es lo que buscamos. En
     * $category->products, products es la relación entre tablas y devuelve una colección y no un modelo, lo que generaría error
     * En su lugar tenemos que hacer que la relación se ejecute desde QueryBuilder para que nos devuelva todas las filas que contengan productos 
     * 
     * Esto se hace ejecutando la *función* y no la relación, añadiendo los paréntesis. 
     * Pero esto devuelve una colección con los productos
     * Como solo nos interesan las transacciones, primeramente vamos a seleccionar productos que tengan, al menos, una transacción (método whereHas)
     * para no coger listas vacías, luego, con las transacciones resultantes (with y get) usamos pluck(), que permite quedarte solo con las colecciones internas (son arrays, dentro de la key "relations")
     * lo que obtenemos al final son nuevamente listas (a diferencia de los vendedores, que obteníamos directamente el vendedor)
     * porque un producto puede tener varias transacciones. Así que, al final, estamos obteniendo un array compuesto por por varios elementos que a su vez
     * son otro array (las transacciones de cada producto). Esto se resuelve usando la función collapse(), que unifica varios arrays en uno solo.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $transactions = $category->products()
            ->whereHas('transactions')
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse();

        return $this->showAll($transactions);
    }
}
