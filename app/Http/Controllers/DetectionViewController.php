<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;

class DetectionViewController extends Controller
{
    /**
   * Devuelve todos los registros.
   */
    public function index()
    {
        $mesActual = Carbon::now()->format('m');
        $mesAnho = strtoupper(Carbon::now()->locale('es')->format('F Y'));
        $data = [
            "carsHoy" => Detection::where('clase', 'car')
                        ->whereDate('fecha', now()->toDateString())
                        ->count(),
            "trucksHoy" => Detection::where('clase', 'truck')
                        ->whereDate('fecha', now()->toDateString())
                        ->count(),
            "carsMes" => Detection::where('clase', 'car')
                        ->whereMonth('fecha', $mesActual)
                        ->count(),
            "trucksMes" => Detection::where('clase', 'truck')
                        ->whereMonth('fecha', $mesActual)
                        ->count(),
            "mesAnho" => $mesAnho,
        ];
        // dd($data);
        return view('home')->with($data);
    }

    /**
   * Devuelve todos los registros.
   */
    public function about()
    {
        return view('about');
    }
}
