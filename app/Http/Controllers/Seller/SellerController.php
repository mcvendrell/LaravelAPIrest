<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Seller;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Son vendedores aquellos usuarios que tienen productos
        $sellers = Seller::has('products')->get();
        
        //return response()->json(['data' => $sellers], 200);
        // Trait en ApiController
        return $this->showAll($sellers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        //Son vendedores aquellos usuarios que tienen productos
        //$seller = Seller::has('products')->findOrFail($id);    // Se quita para usar inyección de dependencias
        
        //return response()->json(['data' => $seller], 200);
        // Trait en ApiController
        return $this->showOne($seller);
    }
}
