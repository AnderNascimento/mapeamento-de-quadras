<?php

namespace App\Http\Controllers;

use App\Models\Square;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SquareController extends Controller
{
    //Mostrando quadras
    public function index(Request $request)
    {
        $squares = Square::query()->with('user')->where('user_id', Auth::user()->id)->get();

        return response()->json($squares);
     }

   //Cadastrando quadras
   public function store(Request $request)
   {
        $square                 = new Square();
        $square->id             = $request->get('id');
        $square->name           = $request->get('name');
        $square->user_id        = $request->auth()->user()->id;
        $square->total_units    = $request->get('total_units');
        $square->polygon        = $request->get('polygon');
        $square->starting_point = $request->get('starting_point');
        $square->save();

        return response()->json($square);
   }

   public function show(int $id)
   {
        $square = Square::with('user')->where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ])->first();

        if(!$square){
            return response()->json(['message' => 'Quadra não encontada!'], 404);
        }

        return response()->json($square);
   }
}
