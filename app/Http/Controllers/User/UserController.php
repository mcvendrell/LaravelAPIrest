<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        
        //return response()->json(['data' => $users], 200);
        // Trait en ApiController
        return $this->showAll($users);
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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3|confirmed',
        ];

        $this->validate($request, $rules);

        $fields = $request->all();
        $fields['password'] = bcrypt($request->password);
        $fields['admin'] = User::USER_NORMAL;
        $fields['verified'] = User::USER_NOT_VERIFIED;
        $fields['verification_token'] = User::generateVerificationToken();

        $user = User::create($fields);

        //return response()->json(['data' => $user], 201);
        // Trait en ApiController
        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //$user = User::findOrFail($id);    // Se quita para usar inyección de dependencias
        
        //return response()->json(['data' => $user], 200);
        // Trait en ApiController
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Obtener el user a actualizar
        //$user = User::findOrFail($id);    // Se quita para usar inyección de dependencias

        $rules = [
            'email' => 'email|unique:users,email,'.$user->id,
            'password' => 'min:3|confirmed',
            'admin' => 'in:'.User::USER_ADMIN.','.User::USER_NORMAL,
        ];

        $this->validate($request, $rules);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::USER_NOT_VERIFIED;
            $user->verification_token = User::generateVerificationToken();

            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                //return response()->json(['error' => 'Solo usuarios verificados pueden cambiar admin', 'code' => 409], 409);
                // Trait en ApiController
                return $this->errorResponse('Solo usuarios verificados pueden cambiar admin', 409);
            }

            $user->admin = $request->admin;
        }
        $fields['admin'] = User::USER_NORMAL;

        if (!$user->isDirty()) {
            //return response()->json(['error' => 'No se cambió ningún valor', 'code' => 422], 422);
            // Trait en ApiController
            return $this->errorResponse('No se cambió ningún valor', 422);
        }

        $user->save();

        //return response()->json(['data' => $user], 200);
        // Trait en ApiController
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //$user = User::findOrFail($id);    // Se quita para usar inyección de dependencias

        $user->delete();
        
        //return response()->json(['data' => $user], 200);
        // Trait en ApiController
        return $this->showOne($user);
    }
}
