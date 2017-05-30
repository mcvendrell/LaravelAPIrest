<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        
        //return response()->json(['data' => $products], 200);
        // Trait en ApiController
        return $this->showAll($products);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //$product = Product::findOrFail($id);    // Se quita para usar inyección de dependencias
        
        //return response()->json(['data' => $product], 200);
        // Trait en ApiController
        return $this->showOne($product);
    }
}
