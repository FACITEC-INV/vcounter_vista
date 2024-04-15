<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DetectionController extends Controller
{
  private $meses = [
    'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO',
    'JULIO', 'AGOSTO', 'SETIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'
  ];

  /**
   * Devuelve la fehca y hora de la última inserción.
   * Se usa para comprobar el último envío desde la cámara
   * @return json { ok: boolean, msg: string }
   */
  public function getLastTransaction(Request $req)
  {
    try {
      $data = Detection::latest('fecha')->first();
      return response()->json([
        'ok' => true,
        'fecha' => $data->fecha,
      ], 200);
    } catch (\Throwable $err) {
      return response()->json([
        'ok' => false,
        'msg' => $err->getMessage(),
      ], 400);
    }
  }

  /**
   * Controller para devolución de detecciones por fechas.
   * Utiliza middlewares para realizar validaciones del token y las fechas
   * @param request header 'API-Token' sha256
   * @param recuest json {desde: date('Y-m-d'), hasta: date('Y-m-d')}
   * @return json { ok: boolean, [msg|data]: [string|json] }
   */
  public function detectiosByDates(Request $req)
  {

    $desde = Carbon::createFromDate($req->json('desde'));
    $hasta = Carbon::createFromDate($req->json('hasta'));

    $detections = Detection::select(
      DB::raw("*")
    )
      ->whereBetween('fecha', [$desde, $hasta])
      ->get();

    return response()->json([
      'ok' => true,
      'consulta' => ['desde' => $desde, 'hasta' => $hasta],
      'cantidad' => count($detections),
      'datos' => $detections
    ], 200);
  }

  /**
   * Devuelve el recuento por fecha.
   * @param recuest json {date: date(Y-m-d H:M:S)}
   * @return json { ok: boolean, [msg|data]: [string|json] }
   */
  public function getByDate(Request $req)
  {
    // NOTE: Para ver todos los parametros en consola del server
    // error_log(json_encode($req->all()));

    $diasLetras = ['DOMINGO', 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO'];
    $fecha = Carbon::createFromDate($req->json('date'));
    try {
      $data = [
        // NOTE: Se comenta por el modelo de detección implementado no clasifica los vehículos
        // "carsDia" => Detection::where('clase', 'car')->whereDate('fecha', $fecha->toDateString())->count(),
        // "trucksDia" => Detection::where('clase', 'truck')->whereDate('fecha', $fecha->toDateString())->count(),
        // "carsMes" => Detection::where('clase', 'car')->whereMonth('fecha', $fecha->month)->count(),
        // "trucksMes" => Detection::where('clase', 'truck')->whereMonth('fecha', $fecha->month) ->count(),
        // "carsAnho" => Detection::where('clase', 'car') ->whereYear('fecha', $fecha->year) ->count(),
        // "trucksAnho" => Detection::where('clase', 'truck') ->whereYear('fecha', $fecha->year) ->count(),
        // HACK: consulta para sqlite:
        // select count(*) from detections where date(fecha) = date('Y-m-d') and id_zona = 'centro';
        "vehicDia" => Detection::whereDate('fecha', $fecha->toDateString())
          ->where('id_zona', 'centro')
          ->count(),
        // HACK: consulta para sqlite:
        // select count(*) from detections where strftime('%m',fecha) = strftime('%m','Y-m-d') and id_zona = 'centro';
        "vehicMes" => Detection::whereMonth('fecha', $fecha->month)
          ->where('id_zona', 'centro')
          ->count(),
        // HACK: consulta para sqlite:
        // select count(*) from detections where strftime('%Y',fecha) = strftime('%Y','Y-m-d') and id_zona = 'centro';
        "vehicAnho" => Detection::whereYear('fecha', $fecha->year)
          ->where('id_zona', 'centro')
          ->count(),
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
        'msg' => $err->getMessage(),
      ], 400);
    }
  }

  /**
   * Devuelve la cantidad de cars y trucks entre dos fechas.
   * Se utiliza para el gráfico
   * @param Request json {desde: date('Y-m-d'), hasta: date('Y-m-d'), vista: string}
   * @return json { ok: boolean, [msg|data]: [string|Array()] }
   */
  public function getBetweenDates(Request $req)
  {
    // NOTE: Para ver todos los parametros en consola del server
    // error_log(json_encode($req->all()));
    try {
      $data = [];

      if ($req->input('vista') == 'DIA') {
        // HACK: consulta para sqlite
        // select strftime('%d', fecha) as dia, strftime('%m', fecha) as mes, strftime('%Y', fecha) as anho, count(*) as cant
        // from detections
        // where fecha between '2024-04-13' and '2024-04-13 23:59:59'
        // and id_zona = 'centro'
        // group by anho, mes, dia;
        $detections = Detection::select(
          DB::raw("strftime('%d', fecha) as dia, strftime('%m', fecha) as mes, strftime('%Y', fecha) as anho, count(*) as cant")
        )
          ->whereBetween('fecha', [$req->input('desde'), $req->input('hasta')])
          ->where('id_zona', 'centro')
          ->groupBy('anho', 'mes', 'dia')
          ->get();

        foreach ($detections as $detection) {
          $mesIndex = intval($detection['mes']) - 1;
          // HACK: substr extra las tres primeras letras del mes
          $label = $detection['dia'] . ' ' . substr($this->meses[$mesIndex], 0, 3) . ' ' . $detection['anho'];
          $data[] = ['label' => $label, 'cant' => $detection['cant']];
        }
      } elseif ($req->input('vista') == 'MES') {
        // HACK: consulta para sqlite
        // select strftime('%m', fecha) as mes, strftime('%Y', fecha) as anho, count(*) as cant
        // from detections
        // where fecha between '2024-04-01' and '2024-04-13 23:59:59'
        // and id_zona = 'centro'
        // group by anho, mes;
        $detections = Detection::select(
          DB::raw("strftime('%m', fecha) as mes, strftime('%Y', fecha) as anho, count(*) as cant")
        )
          ->whereBetween('fecha', [$req->input('desde'), $req->input('hasta')])
          ->where('id_zona', 'centro')
          ->groupBy('anho', 'mes')
          ->get();

        foreach ($detections as $detection) {
          $mesIndex = intval($detection['mes']) - 1;
          $label = $this->meses[$mesIndex] . ' ' . $detection['anho'];
          $data[] = ['label' => $label, 'cant' => $detection['cant']];
        }
      } else {
        // HACK: consulta para sqlite
        // select strftime('%Y', fecha) as anho, count(*) as cant
        // from detections
        // where fecha between '2024-04-01' and '2024-04-13 23:59:59'
        // and id_zona = 'centro'
        // group by anho;
        error_log($req->input('vista'));
        $detections = Detection::select(
          DB::raw("strftime('%Y', fecha) as anho, count(*) as cant")
        )
          ->whereBetween('fecha', [$req->input('desde'), $req->input('hasta')])
          ->where('id_zona', 'centro')
          ->groupBy('anho')
          ->get();

        foreach ($detections as $detection) {
          $label = $detection['anho'];
          $data[] = ['label' => $label, 'cant' => $detection['cant']];
        }
      }

      return response()->json([
        'ok' => true,
        'data' => $data,
      ], 200);
    } catch (\Throwable $err) {
      return response()->json([
        'ok' => false,
        'msg' => $err->getMessage(),
      ], 400);
    }
  }

  /**
   * Guarda un registro.
   * @param Request json [{id_zona: string,  clase: string, fecha: date('Y-m-d')},...]
   * @return json { ok: boolean, msg: string }
   */
  public function store(Request $request)
  {
    // INFO: validaciones
    $reglas = [
      'detections' => 'present|array',
      'detections.*.id_zona' => 'required|string',
      'detections.*.clase' => 'required|string',
    ];

    $validacion = \Validator::make($request->all(), $reglas);

    if ($validacion->fails()) {
      return response()->json([
        'ok' => false,
        'msg' => $validacion->errors()->all()
      ], 400);
    }

    // HACK: Divide la divide la inserciones
    // ver: https://www.sqlite.org/limits.html, https://github.com/laravel/framework/issues/50#issue-9976572
    try {
      $detections = $request->input('detections');
      $rows = count($detections);

      if ($rows > 300) {
        DB::transaction(function () use ($detections) {
          $chunk = array_chunk($detections, 300);
          foreach ($chunk as $dets) {
            Detection::insert($dets);
          }
        });

        return response()->json([
          'ok' => true,
          'msg' => 'Se guardaron los datos. Cant. registros: ' . $rows,
        ], 200);
      } else {
        Detection::insert($detections);
        return response()->json([
          'ok' => true,
          'msg' => 'Se guardaron los datos. Cant. registros: ' . $rows,
        ], 200);
      }
    } catch (\Throwable $err) {
      return response()->json([
        'ok' => false,
        'msg' => 'No se guardaron los datos. Cant. registros: ' . $rows,
        'error' => $err->getMessage()
      ], 400);
    }
  }
}
