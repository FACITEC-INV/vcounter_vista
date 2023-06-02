<?php

use App\Http\Controllers\DetectionController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('detections/getByDate', [DetectionController::class, 'getByDate']);
Route::post('detections/getBetweenDates', [DetectionController::class, 'getBetweenDates']);
Route::post('detections/add', [DetectionController::class, 'store']);
