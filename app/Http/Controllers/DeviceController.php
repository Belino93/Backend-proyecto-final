<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    // Get All devices
    public function getDevices()
    {
        Log::info('Retrieving devices');
        try {
            $devices = Device::all();
            return response([
                'success' => true,
                'message' => 'All devices retrieved successfully',
                'data' => $devices,
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong retrieving devices',
            ], 400);
        }
    }

    // Get by brand
    public function getDevicesByBrand(Request $request)
    {
        Log::info('Retrieving devices by brand');
        try {
            $validator = Validator::make($request->all(), [
                'brand' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], 400);
            }
            $brand = $request->input('brand');
            $devices = Device::where('brand', $brand)->get();
            dd($devices);
            if(!$devices){
                return response([
                    'success' => true,
                    'message'=> 'This brand is not in database'
                ], 400);
            }

            return response([
                'success' => true,
                'message' => 'Devices by brand retrieve successfully',
                'data' => $devices
            ],200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong retrieving devices by branch',
            ], 400);
        }
    }

    public function newDevice(Request $request)
    {
        Log::info('Creating new device');
        $validator = Validator::make($request->all(), [
            'branch' => 'required|string|max:255',
            'model'
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->messages()
            ], 400);
        }
    }
}
