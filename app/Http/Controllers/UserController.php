<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user           = new User();
        $user->name     = $request->get('name');
        $user->email    = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->save();

        return response()->json($user);
    }

    public function show(int $id, Request $request)
    {
        $user = User::query()->find($id);

        return response()->json($user);
    }

    public function update(int $id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::query()->find($id);

        if(!$user){
            return response()->json(["message" => "Usuário não encontrado"]);
        }

        $user->name     = $request->get('name');
        $user->email    = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->update();

        return response()->json($user);
    }

    public function destroy(int $id)
    {
        $user = User::query()->find($id);

        if(!$user){
            return response()->json(["message" => "Usuário não encontrado"], 404);
        }

        if($user->id == 1){
            return response()->json(["message" => "Usuário admin não pode ser excluído"], 403);
        }

        $user->delete();

        return response()->json(["message" => "Usuário excluído com sucesso"], 200);
    }
}
