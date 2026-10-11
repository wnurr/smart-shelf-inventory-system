<?php

use App\Http\Controllers\Api\CalibrationCommandController;
use App\Http\Controllers\Api\ReadingController;
use Illuminate\Support\Facades\Route;

Route::get('/sensors/{sensorCode}/config', [ReadingController::class, 'sensorConfig']);

Route::post('/readings', [ReadingController::class, 'store']);
Route::post('/readings/live', [ReadingController::class, 'storeLive']);
Route::get('/readings/live', [ReadingController::class, 'live']);
Route::get('/readings/latest', [ReadingController::class, 'latest']);

Route::get('/calibration/commands/pending', [CalibrationCommandController::class, 'pending']);
Route::post('/calibration/commands/{command}/complete', [CalibrationCommandController::class, 'complete']);
