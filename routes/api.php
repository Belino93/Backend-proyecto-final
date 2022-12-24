<?php

use App\Http\Controllers\DeviceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Devices CRUD endpoints
Route::get('/devices', [DeviceController::class, 'getDevices']);
Route::patch('/devices', [DeviceController::class, 'updateDevice']);
Route::delete('devices', [DeviceController::class, 'deleteDevice']);

Route::post('/devices/branch', [DeviceController::class, 'getDevicesByBrand']);
Route::post('/devices/new', [DeviceController::class, 'newDevice']);
