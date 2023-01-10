<?php

namespace App\Http\Controllers;


use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Something went wrong retrieving devices',
            ], Response::HTTP_BAD_REQUEST);
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
                ], Response::HTTP_BAD_REQUEST);
            }
            $brand = $request->input('brand');
            $devices = Device::where('brand', $brand)->get();

            if (!$devices) {
                return response([
                    'success' => true,
                    'message' => 'This brand is not in database'
                ], Response::HTTP_BAD_REQUEST);
            }

            return response([
                'success' => true,
                'message' => 'Devices by brand retrieve successfully',
                'data' => $devices
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Something went wrong retrieving devices by branch',
            ], Response::HTTP_BAD_REQUEST);
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
                ], Response::HTTP_BAD_REQUEST);
            }
            $device = new Device;

            $device->brand = $request->input('brand');
            $device->model = $request->input('model');
            $device->save();

            return response([
                'success' => true,
                'message' => 'Device created successfully',
                'data' => $device
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Something went wrong creating devices',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // Update device

    public function updateDevice(Request $request)
    {
        Log::info('Updating device');

        try {
            $validator = Validator::make($request->all(), [
                'device_id' => 'required|integer',
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], Response::HTTP_BAD_REQUEST);
            }
            $device = Device::find($request->input('device_id'));

            if (!$device) {
                return response([
                    'success' => true,
                    'message' => 'device_id dont match'
                ], Response::HTTP_BAD_REQUEST);
            }

            $device->brand = $request->input('brand');
            $device->model = $request->input('model');
            $device->save();

            return response([
                'success' => true,
                'message' => 'Device updated'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Something went wrong updating device',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // Delete device
    public function deleteDevice(Request $request)
    {
        Log::info('Deleting device');
        try {
            $validator = Validator::make($request->all(), [
                'device_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], Response::HTTP_BAD_REQUEST);
            }

            $device = Device::find($request->input('device_id'));
            if (!$device) {
                return response([
                    'success' => true,
                    'message' => 'device_id dont match'
                ], Response::HTTP_BAD_REQUEST);
            }
            $device->delete();

            return response([
                'success' => true,
                'message' => 'Device dropped succesfully'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Something went wrong dropping device',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // Get brands 
    public function getBrands()
    {
        Log::info('Getting device brands');

        try {
            $brands = DB::table('devices')->select('brand')->distinct()->get();

            return response([
                'success' => true,
                'message' => 'Retrieving brands successfully',
                'data' => $brands
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Something went wrong retrieving brands',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
