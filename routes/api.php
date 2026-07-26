<?php

use App\Http\Controllers\Api\ReadingController;
use Illuminate\Support\Facades\Route;

// Called continuously by the ESP32 with the raw shelf weight.
Route::post('/readings', [ReadingController::class, 'store']);

// Called once during setup, after placing exactly one unit on the shelf.
Route::post('/readings/calibrate-unit', [ReadingController::class, 'calibrateUnit']);