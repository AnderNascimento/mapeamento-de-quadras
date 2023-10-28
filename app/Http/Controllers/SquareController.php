<?php

namespace App\Http\Controllers;

use App\Models\Square;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

use \DantSu\OpenStreetMapStaticAPI\OpenStreetMap;
use \DantSu\OpenStreetMapStaticAPI\LatLng;
use \DantSu\OpenStreetMapStaticAPI\Polygon;
use \DantSu\OpenStreetMapStaticAPI\Markers;
use PhpParser\JsonDecoder;

class SquareController extends Controller
{
    //Lista quadras
    public function index(Request $request)
    {
        $squares = Square::query()->with('user')->where('user_id', Auth::user()->id)->get();

        return response()->json($squares);
     }

   //Cadastra quadras
   public function store(Request $request)
   {
        $request->validate([
            'name' => 'required|string',
            'total_units' => 'required|integer',
            'polygon' => 'required|json',
            'starting_point' => 'required|string'
        ]);

        $square                 = new Square();
        $square->id             = $request->get('id');
        $square->name           = $request->get('name');
        $square->user_id        = auth()->user()->id;
        $square->total_units    = $request->get('total_units');
        $square->polygon        = $request->get('polygon');
        $square->starting_point = $request->get('starting_point');
        $square->save();

        return response()->json($square);
   }
   //Busca quadras
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
   //Atualiza quadras
   public function update(int $id, Request $request)
   {
        $request->validate([
            'name' => 'required|string',
            'total_units' => 'required|integer',
            'polygon' => 'required|json',
            'starting_point' => 'required|string'
        ]);

        $square = Square::query()->where('user_id', Auth::user()->id)->find($id);
        $square->name           = $request->get('name');
        $square->total_units    = $request->get('total_units');
        $square->polygon        = $request->get('polygon');
        $square->starting_point = $request->get('starting_point');
        $square->update();

        return response()->json($square);
   }
   //Deleta quadras
   public function destroy(int $id)
   {
        $square = Square::query()->where('user_id', Auth::user()->id)->find($id);

        if(!$square){
            return response()->json(['message' => 'Quadra não encontada!'], 404);
        }

        $square->delete();

        return response()->json(['message' => 'Quadra deletada com sucesso!'], 200);
   }
   //Exporta quadras
   public function export()
   {
        /*
        $user = Auth::user();
        $squares = Square::query()->where('user_id', Auth::user()->id)->get();
        $pdf = PDF::loadView('export', compact('user','squares'));

        */
        $square = Square::query()->find(1);
        $polygon = json_decode($square->polygon);

        $polygonArray =[];

        foreach ($polygon as $poly) {
            $polygonArray[] = new LatLng($poly[1], $poly[0]);
        }

        \header('Content-type: image/png');
        (new OpenStreetMap(new LatLng( -10.7475543, -37.8078148), 16, 400, 400))
            ->addDraw(
                (new Polygon('FF0000', 2, 'FF0000DD'))
                    ->addPoint($polygonArray[1])
                    ->addPoint($polygonArray[2])
                    ->addPoint($polygonArray[3])
                    ->addPoint($polygonArray[4])
                    ->addPoint($polygonArray[5])
                    ->addPoint($polygonArray[6])
                    ->addPoint($polygonArray[7])
            )
            ->getImage()
            ->displayPNG();
   }
}
