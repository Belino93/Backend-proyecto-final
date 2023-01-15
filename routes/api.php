<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceRepairController;
use App\Http\Controllers\EmailController;
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

// Auth public endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/devices', [DeviceController::class, 'getDevices']);
Route::post('/devices/brand', [DeviceController::class, 'getDevicesByBrand']);
Route::get('/devices/brand', [DeviceController::class, 'getBrands']);
Route::get('/repairs', [RepairController::class, 'getAllRepairs']);


// Devices CRUD endpoints
Route::group([
    'middleware' => 'jwt.auth'
], function () {
    // Route::get('/devices', [DeviceController::class, 'getDevices']);
    // Route::post('/devices/brand', [DeviceController::class, 'getDevicesByBrand']);
    // Route::get('/devices/brand', [DeviceController::class, 'getBrands']);
});

//JWT user endpoints
Route::group([
    'middleware' => 'jwt.auth'
], function () {
    Route::patch('/users', [UserController::class, 'updateUser']);

});

// JWT user repairs endpoints
Route::group([
    'middleware' => 'jwt.auth'
], function () {
    Route::get('/user/repairs', [DeviceRepairController::class, 'getUserRepairs']);
    Route::post('/user/repairs', [DeviceRepairController::class, 'newDeviceRepair']);
    Route::post('/user/repairs/imei', [DeviceRepairController::class, 'getAllUserRepairByImei']);
});

// Auth endpoint
Route::group([
    'middleware' => 'jwt.auth'
], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
});

// Admin endpoint
Route::group([
    'middleware' => ['jwt.auth', 'isAdmin']
], function () {
    // User repairs
    Route::patch('/user/repairs', [DeviceRepairController::class, 'updateUserRepair']);
    Route::patch('/user/repairs/next', [DeviceRepairController::class, 'nextRepairState']);
    Route::patch('/user/repairs/prev', [DeviceRepairController::class, 'prevRepairState']);
    Route::get('/admin/repairs', [DeviceRepairController::class, 'getAllUsersRepairs']);
    Route::delete('/users', [UserController::class, 'deleteUser']);
    Route::patch('/users/admin', [UserController::class, 'userUpdateRole']);

    // User endpoints(ADMIN)
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::delete('/users/delete', [UserController::class, 'deleteUserByAdmin' ]);

    // Repairs CRUD endpoints
    Route::post('/repairs', [RepairController::class, 'newRepair']);
    Route::patch('/repairs', [RepairController::class, 'updateRepair']);
    Route::delete('/repairs', [RepairController::class, 'deleteRepair']);
    // Devices endpoint
    Route::post('/devices/new', [DeviceController::class, 'newDevice']);
    Route::patch('/devices', [DeviceController::class, 'updateDevice']);
    Route::delete('/devices', [DeviceController::class, 'deleteDevice']);
});
