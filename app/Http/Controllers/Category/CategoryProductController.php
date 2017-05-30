<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        //Son compradores aquellos usuarios que tienen transacciones realizadas
        $products = $category->products;
        
        //return response()->json(['data' => $buyers], 200);
        // Trait en ApiController
        return $this->showAll($products);
    }

}
