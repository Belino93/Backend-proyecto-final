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
            if (!$devices) {
                return response([
                    'success' => true,
                    'message' => 'This brand is not in database'
                ], 400);
            }

            return response([
                'success' => true,
                'message' => 'Devices by brand retrieve successfully',
                'data' => $devices
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong retrieving devices by branch',
            ], 400);
        }
    }

    // New device
    public function newDevice(Request $request)
    {
        Log::info('Creating new device');
        try {
            $validator = Validator::make($request->all(), [
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], 400);
            }
            $device = new Device;

            $device->brand = $request->input('brand');
            $device->model = $request->input('model');
            $device->save();

            return response([
                'success' => true,
                'message' => 'Device created successfully',
                'data' => $device
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong creating devices',
            ], 400);
        }
    }

    // Update device

    public function updateDevice(Request $request)
    {
        Log::info('Updating device');

        try {
            $validator = Validator::make($request->all(), [
                'device_id'=> 'required|integer',
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], 400);
            }
            $device = Device::find($request->input('device_id'));

            if(!$device){
                return response([
                    'success' => true,
                    'message' => 'device_id dont match'
                ], 400);
            }

            $device -> brand = $request->input('brand');
            $device -> model = $request->input('model');
            $device->save();
            return response([
                'success' => true,
                'message' => 'Device updated'
            ], 200);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong updating device',
            ], 400);
        }
        
    }

    // Delete device
    public function deleteDevice(Request $request)
    {
        Log::info('Creating new device');
        try {
            $validator = Validator::make($request->all(), [
                'device_id'=> 'required|integer',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], 400);
            }

            $device = Device::find($request->input('device_id'));
            if(!$device){
                return response([
                    'success' => true,
                    'message' => 'device_id dont match'
                ], 400);
            }
            $device->delete();
            return response([
                'success' => true,
                'message' => 'Device dropped succesfully'
            ], 200);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong dropping device',
            ], 400);
        }
    }
}
