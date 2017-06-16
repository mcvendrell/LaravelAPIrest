<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Queremos mostrar los compradores para una categoría
     * El problema es que una categoría tiene varios productos, y estos están asaciados a una transacción que tiene un comprador 
     * ... o puede que no, si nunca han sido vendidos (el comprador nunca los ha comprado)
     * Esto nos puede dar compradores vacíos y no es lo que buscamos. En
     * $category->products, products es la relación entre tablas y devuelve una colección y no un modelo, lo que generaría error
     * En su lugar tenemos que hacer que la relación se ejecute desde QueryBuilder para que nos devuelva todas las filas que contengan productos 
     * 
     * Esto se hace ejecutando la *función* y no la relación, añadiendo los paréntesis. 
     * Pero esto devuelve una colección con los productos
     * Como solo nos interesan los compradores, primeramente vamos a seleccionar productos que tengan, al menos, una transacción (método whereHas)
     * para no coger listas vacías, luego, con los compradores (que vienen de la transaccion) resultantes (with y get) usamos pluck(), que permite quedarte solo con las colecciones internas que se indiquen (son arrays, dentro de la key "relations")
     * lo que obtenemos al final son nuevamente listas (a diferencia de los vendedores, que obteníamos directamente el vendedor)
     * porque un producto puede tener varias transacciones (y por tanto compradores). Así que, al final, estamos obteniendo un array compuesto por por varios elementos que a su vez
     * son otro array (las transacciones de cada producto). Esto se resuelve usando la función collapse(), que unifica varios arrays en uno solo.
     * Una vez obtenido un único array con todas las transacciones, ahora sí queremos "extraer" solo los compradores, que es lo que nos interesa al final
     * De todos los compradores, es posible que haya repetidos (el mismo comprador puede comprar varias veces el mismo producto), así que hacemos que la lista sea
     * única (lo que nos obliga a eliminar los huecos vacíos con values())
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $buyers = $category->products()
            ->whereHas('transactions')
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions')
            ->collapse()
            ->pluck('buyer')
            ->unique()
            ->values();

        return $this->showAll($buyers);
    }
}

