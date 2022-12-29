<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceRepairController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\UserController;
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

// User endpoints(ADMIN)
Route::get('/users', [UserController::class, 'getUsers']);

//JWT user endpoints
Route::group([
    'middleware' => 'jwt.auth'
], function () {
    Route::patch('/users', [UserController::class, 'updateUser']);
    Route::delete('/users', [UserController::class, 'deleteUser']);
});

// JWT user repairs endpoints
Route::group([
    'middleware' => 'jwt.auth'
], function () {
    Route::get('/user/repairs', [DeviceRepairController::class, 'getUserRepairs']);
    Route::post('/user/repairs', [DeviceRepairController::class, 'newDeviceRepair']);
    Route::patch('/user/repairs', [DeviceRepairController::class, 'updateUserRepair']);
    
    Route::patch('/user/repairs/next', [DeviceRepairController::class, 'nextRepairState']);
    Route::patch('/user/repairs/prev', [DeviceRepairController::class, 'prevRepairState']);
    
});


// Auth endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::group([
    'middleware' => 'jwt.auth'
], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
});
