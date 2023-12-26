<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class ValidaFechas
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $req, Closure $next): Response
    {
        // NOTE: Verifica que existan las dos fechas
        if(!$req->has('desde') && !$req->has('hasta')){
             return response()->json([
                'ok' => false,
                'msg' => 'Se espera un request json: {"desde": "yyyy-mm-dd hh:mm:ss", "hasta": "yyyy-mm-dd hh:mm:ss"}',
            ], 400);
        }

        // NOTE: Verifica que existan la fecha desde
        if(!$req->has('desde') && $req->has('hasta')){
             return response()->json([
                'ok' => false,
                'msg' => 'Falta fecha desde! Se espera un request json: {"desde": "yyyy-mm-dd hh:mm:ss", "hasta": "yyyy-mm-dd hh:mm:ss"}',
            ], 400);
        }

        // NOTE: Verifica que existan la fecha hasta
        if($req->has('desde') && !$req->has('hasta')){
             return response()->json([
                'ok' => false,
                'msg' => 'Falta fecha hasta! Se espera un request json: {"desde": "yyyy-mm-dd hh:mm:ss", "hasta": "yyyy-mm-dd hh:mm:ss"}',
            ], 400);
        }

        $desde = Carbon::createFromDate($req->json('desde'));
        $hasta = Carbon::createFromDate($req->json('hasta'));

        // NOTE: Verifica que la fecha desde sea menor a la fecha hasta
        if($desde->greaterThan($hasta)){
             return response()->json([
                'ok' => false,
                'msg' => 'Error en las fechas! Se requiere desde <= hasta',
            ], 400);
        }

        return $next($req);
    }
}
