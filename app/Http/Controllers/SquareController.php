<?php

namespace App\Http\Controllers;

use App\Models\Square;
use Barryvdh\DomPDF\Facade\Pdf;
use DantSu\OpenStreetMapStaticAPI\LatLng;
use DantSu\OpenStreetMapStaticAPI\OpenStreetMap;
use DantSu\OpenStreetMapStaticAPI\Polygon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SquareController extends Controller
{
    //Lista quadras
    public function index(Request $request)
    {
        $squares = Square::query()->with('user')->where('user_id', Auth::user()->id)->get();

        return response()->json($squares);
    }

    //Cadastra quadra
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'total_units' => 'required|integer',
            'polygon' => 'required|json',
            'starting_point' => 'required|string',
        ]);

        $square = new Square();
        $square->id = $request->get('id');
        $square->name = $request->get('name');
        $square->user_id = auth()->user()->id;
        $square->total_units = $request->get('total_units');
        $square->polygon = $request->get('polygon');
        $square->starting_point = $request->get('starting_point');
        $square->save();

        return response()->json($square);
    }

    //Busca quadra
    public function show(int $id)
    {
        $square = Square::with('user')->where([
            'user_id' => Auth::user()->id,
            'id' => $id,
        ])->first();

        if (! $square) {
            return response()->json(['message' => 'Quadra não encontada!'], 404);
        }

        return response()->json($square);
    }

    //Atualiza quadra
    public function update(int $id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'total_units' => 'required|integer',
            'polygon' => 'required|json',
            'starting_point' => 'required|string',
        ]);

        $square = Square::query()->where('user_id', Auth::user()->id)->find($id);
        $square->name = $request->get('name');
        $square->total_units = $request->get('total_units');
        $square->polygon = $request->get('polygon');
        $square->starting_point = $request->get('starting_point');
        $square->update();

        return response()->json($square);
    }

    //Deleta quadra
    public function destroy(int $id)
    {
        $square = Square::query()->where('user_id', Auth::user()->id)->find($id);

        if (! $square) {
            return response()->json(['message' => 'Quadra não encontada!'], 404);
        }

        $square->delete();

        return response()->json(['message' => 'Quadra deletada com sucesso!'], 200);
    }

    //Exporta o mapa para PDF
    public function export()
    {
        $square = Square::query()->find(1);

        $image = $this->createMap($square);

        $pdf = PDF::loadView('export', compact('square', 'image'));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('quadras.pdf', ['Attachment' => false]);
    }

    //Cria imagem da quadra no mapa
    private function createMap(Square $square)
    {
        $polygon = json_decode($square->polygon);

        $polygonArray = [];

        foreach ($polygon as $poly) {
            $polygonArray[] = new LatLng($poly[1], $poly[0]);
        }

        $perimeter = new Polygon('FF0000', 2, 'FF0000DD');
        $perimeter->addPoint($polygonArray[1]);
        $perimeter->addPoint($polygonArray[2]);
        $perimeter->addPoint($polygonArray[3]);
        $perimeter->addPoint($polygonArray[4]);
        $perimeter->addPoint($polygonArray[5]);
        $perimeter->addPoint($polygonArray[6]);
        $perimeter->addPoint($polygonArray[7]);

        try {
            $osm = new OpenStreetMap(new LatLng(-10.7475543, -37.8078148), 16, 600, 400);
            $image = $osm->addDraw($perimeter)
                ->getImage()
                ->getBase64JPG();
        } catch (\Throwable $th) {
            throw $th;
        }

        return $image;
    }
}
