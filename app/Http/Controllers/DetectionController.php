<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DetectionController extends Controller
{
    private $meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO',
            'JULIO', 'AGOSTO', 'SETIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    /**
   * Devuelve el recuento por fecha.
   * @param {date: date(Y-m-d)}
   * @return json [
   *                {carsDia: int, trucksDia: int},
   *                {carsMes: int, trucksMes: int},
   *                {carsAnho: int, trucksAnho: int},
   *                {mesLetras: string},
   *            ]
   */
    public function getByDate(Request $req)
    {
        $diasLetras = ['DOMINGO', 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO'];
        error_log($req->input('date'));
        $fecha = Carbon::createFromDate($req->input('date'));

        try {
            $data = [
                "carsDia" => Detection::where('clase', 'car')->whereDate('fecha', $fecha->toDateString())->count(),
                "trucksDia" => Detection::where('clase', 'truck')->whereDate('fecha', $fecha->toDateString())->count(),
                "carsMes" => Detection::where('clase', 'car')->whereMonth('fecha', $fecha->month)->count(),
                "trucksMes" => Detection::where('clase', 'truck')->whereMonth('fecha', $fecha->month) ->count(),
                "carsAnho" => Detection::where('clase', 'car') ->whereYear('fecha', $fecha->year) ->count(),
                "trucksAnho" => Detection::where('clase', 'truck') ->whereYear('fecha', $fecha->year) ->count(),
                "mes" => $this->meses[$fecha->month - 1],
                "dia" => $diasLetras[$fecha->dayOfWeek] . ' ' . $fecha->day,
                "anho" => $fecha->year,
            ];
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
   * Cantidad de cars y trucks entre dos fechas.
   * @param Request {desde: date('Y-m-d'), hasta: date('Y-m-d')}
   */
  public function getBetweenDates(Request $req)
  {
    try {
        if ($req->input('vista') == 'DIA') {
// select strftime('%d', fecha) as dia, strftime('%m', fecha) as mes, count(*) as cant
// from detections
// where fecha between '2023-05-01' and '2023-05-10'
// group by mes, dia;
            $detections = Detection::select(
                DB::raw("strftime('%d', fecha) as dia, strftime('%m', fecha) as mes, count(*) as cant"))
                ->whereBetween('fecha', [$req->input('desde'), $req->input('hasta')])
                ->groupBy('mes', 'dia')
                ->get();
        } elseif ($req->input('vista') == 'MES'){
// select strftime('%m', fecha) as mes, strftime('%Y', fecha) as anho, count(*) as cant
// from detections
// group by mes;
                // TODO: Adecuar a consulta por mes
            $detections = Detection::select(
                DB::raw("strftime('%d', fecha) as dia, strftime('%m', fecha) as mes, count(*) as cant"))
                ->whereBetween('fecha', [$req->input('desde'), $req->input('hasta')])
                ->groupBy('mes', 'dia')
                ->get();
        } else {
// select strftime('%Y', fecha) as anho, count(*) as cant
// from detections
// group by anho;
                // TODO: Adecuar a consulta por anho
            $detections = Detection::select(
                DB::raw("strftime('%d', fecha) as dia, strftime('%m', fecha) as mes, count(*) as cant"))
                ->whereBetween('fecha', [$req->input('desde'), $req->input('hasta')])
                ->groupBy('mes', 'dia')
                ->get();
        }

        $data = [];
        foreach($detections as $detection){
            $mesIndex = intval($detection['mes'])-1;
            $dia = $this->meses[$mesIndex]. ' ' . $detection['dia'];
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
