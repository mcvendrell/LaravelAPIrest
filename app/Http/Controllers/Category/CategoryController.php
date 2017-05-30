<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Son compradores aquellos usuarios que tienen transacciones realizadas
        $categories = Category::all();
        
        //return response()->json(['data' => $buyers], 200);
        // Trait en ApiController
        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
        ];

        $this->validate($request, $rules);

        $fields = $request->all();
        $category = Category::create($fields);

        //return response()->json(['data' => $category], 201);
        // Trait en ApiController
        return $this->showOne($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //return response()->json(['data' => $buyer], 200);
        // Trait en ApiController
        return $this->showOne($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // Obtener el category a actualizar
        //$category = category::findOrFail($id);    // Se quita para usar inyección de dependencias

        //Como no hay reglas especiales, pasamos de comprobar nada

        // Con el método fill rellenamos uno o más valores de los pasados si han sido cambiados
        // Con intersect se obtienen solo los valores esperados (por si el usuario envió más valores en la lista)
        $category->fill($request->intersect([
            'name',
            'description',
        ]));

        if (!$category->isDirty()) {
            //return response()->json(['error' => 'No se cambió ningún valor', 'code' => 422], 422);
            // Trait en ApiController
            return $this->errorResponse('No se cambió ningún valor', 422);
        }

        $category->save();

        //return response()->json(['data' => $category], 200);
        // Trait en ApiController
        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //$category = category::findOrFail($id);    // Se quita para usar inyección de dependencias

        $category->delete();
        
        //return response()->json(['data' => $category], 200);
        // Trait en ApiController
        return $this->showOne($category);
    }
}
