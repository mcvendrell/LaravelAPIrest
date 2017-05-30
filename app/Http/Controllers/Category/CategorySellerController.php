<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategorySellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Queremos mostrar los vendedores que ha tenido una categoría
     * El problema es que una categoría tiene varios productos, y estos están asaciados a un vendedor
     * Esto nos puede dar vendedores repetidos. En
     * $category->products, products es la relación entre tablas y devuelve una colección y no un modelo, lo que generaría error
     * En su lugar tenemos que hacer que la relación se ejecute desde QueryBuilder para que nos devuelva todas las filas que contengan productos 
     * 
     * Esto se hace ejecutando la *función* y no la relación, añadiendo los paréntesis. 
     * Pero esto devuelve una colección con los productos
     * Como solo nos interesan los vendedores, usamos pluck(), que permite quedarte solo con las colecciones internas (son arrays, dentro de la key "relations")
     * Como además, no queremos que se repitan vendedores, usamos unique(), escogiendo el id. El problema es que unique deja elementos vacíos en el array final
     * para evitar las repeticiones, así que añadimos la función values() que obtiene solo los elementos con valor en un nuevo array ordenado.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $sellers = $category->products()->with('seller')
            ->get()
            ->pluck('seller')
            ->unique('id')
            ->values();

        return $this->showAll($sellers);
    }
}
