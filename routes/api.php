<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RepairController;
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

Route::post('/devices/brand', [DeviceController::class, 'getDevicesByBrand']);
Route::get('/devices/brand', [DeviceController::class, 'getBrands']);
Route::post('/devices/new', [DeviceController::class, 'newDevice']);

// Repairs CRUD endpoints
Route::get('/repairs', [RepairController::class, 'getAllRepairs']);
Route::post('/repairs', [RepairController::class, 'newRepair']);
Route::patch('/repairs', [RepairController::class, 'updateRepair']);
Route::delete('/repairs', [RepairController::class, 'deleteRepair']);