<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetectionController extends Controller
{
  /**
   * Cantidad de cars y trucks entre dos fechas.
   * @param Request {desde: date('Y-m-d'), hasta: date('Y-m-d')}
   */
  public function getBetweenDates(Request $req)
  {
    // return response()->json($req->all());
    $mese = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO',
            'JULIO', 'AGOSTO', 'SETIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    try {
        $detections = Detection::select(
            DB::raw("strftime('%d', fecha) as dia, strftime('%m', fecha) as mes, count(*) as cant"))
            ->whereBetween('fecha', [$req->input('desde'), $req->input('hasta')])
            ->groupBy('mes', 'dia')
            ->get();

        $data = [];
        foreach($detections as $detection){
            $mesIndex = intval($detection['mes'])-1;
            $dia = $mese[$mesIndex]. ' ' . $detection['dia'];
            $data[]=['label' => $dia, 'cant' => $detection['cant']];
        }

        return response()->json([
            'ok' => true,
            'data' => $data,
        ], 200);
    } catch (\Throwable $err) {
        return response()->json([
            'ok' => false,
            'msg' => $err,
        ], 400);
    }
  }

  /**
   * Guarda un registro.
   * @param Request [{id_tracking: string,  clase: string, fecha: date('Y-m-d')},...]
   */
  public function store(Request $request)
  {
    $reglas = [
      'detections' => 'present|array',
      'detections.*.id_tracking' => 'required|string',
      'detections.*.clase' => 'required|string',
    ];

    $validacion = \Validator::make($request->all(), $reglas);

    if ($validacion->fails()) {
      return response()->json([
        'ok' => false,
        'msg' => $validacion->errors()->all()
      ], 400);
    }

    try {
      Detection::insert($request->input('detections'));
      return response()->json([
        'ok' => true,
        'msg' => 'Se guardaron los datos',
      ], 200);
    } catch (\Throwable $err) {
      return response()->json([
        'ok' => false,
        'msg' => 'No se pudieron guardar los datos ' . $err,
      ], 400);
    }
  }
}
