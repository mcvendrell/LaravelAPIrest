<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Son compradores aquellos usuarios que tienen transacciones realizadas
        $buyers = Buyer::has('transactions')->get();
        
        //return response()->json(['data' => $buyers], 200);
        // Trait en ApiController
        return $this->showAll($buyers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        //Son compradores aquellos usuarios que tienen transacciones realizadas
        //$buyer = Buyer::has('transactions')->findOrFail($id);    // Se quita para usar inyección de dependencias
        
        //return response()->json(['data' => $buyer], 200);
        // Trait en ApiController
        return $this->showOne($buyer);
    }
}
