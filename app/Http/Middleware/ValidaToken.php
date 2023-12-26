<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidaToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $req, Closure $next): Response
    {
        // NOTE: Valida header
        if (!$req->hasHeader('API-Token')) {
            return response()->json([
                'ok' => false,
                'msg' => 'Falta token',
            ], 400);
        }

        $apiKey = env('API_KEY');
        $token = $req->header('Api-Token');
        // NOTE: Valida token
        if (!($token == $apiKey)) {
            return response()->json([
                'ok' => false,
                'msg' => 'Token inválido',
            ], 400);
        }
        return $next($req);
    }
}
