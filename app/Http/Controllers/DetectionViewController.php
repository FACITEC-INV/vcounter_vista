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
        return view('home');
    }

    /**
   * Devuelve todos los registros.
   */
    public function about()
    {
        return view('about');
    }
}
