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
    public function export(int $id)
    {
        $square = Square::query()->with('user')->find($id);

        $image = $this->createMap($square);

        $pdf = PDF::loadView('export', compact('square', 'image'));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('quadras.pdf', ['Attachment' => false]);
    }

    //Cria imagem da quadra no mapa
    private function createMap(Square $square)
    {
        $startingPoint = $square->starting_point;
        $perimeter = new Polygon('FF0000', 2, 'FF0000DD');

        $polygonArray = [];

        foreach ($square->polygon as $poly) {
            $latLgn = new LatLng($poly[1], $poly[0]);

            $polygonArray = $latLgn;

            $perimeter->addPoint($polygonArray);
        }

        try {
            if (isset($startingPoint)) {
                $startingPoint = new LatLng($startingPoint[1], $startingPoint[0]);
            } else {
                $startingPoint = new LatLng($poly[1], $poly[0]);
            }

            $osm = new OpenStreetMap($startingPoint, 16, 600, 400);
            $image = $osm->addDraw($perimeter)
                ->getImage()
                ->getBase64JPG();

        } catch (\Throwable $th) {
            throw $th;
        }

        return $image;
    }
}
