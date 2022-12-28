<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DeviceRepairController extends Controller
{
    // Get all deviceRepair (admin)
    public function getUserRepairs(Request $request)
    {
        Log::info('Getting user repairs');
        try {
            $userId = auth()->user()->id;
            $repairs = DB::table('device_repair')
                ->join('devices', 'device_repair.device_id', '=', 'devices.id')
                ->join('repairs', 'device_repair.repair_id', '=', 'repairs.id')
                ->select('devices.brand', 'devices.model', 'repairs.type', 'device_repair.imei')
                ->where('user_id', '=', $userId)
                ->get();
            if (count($repairs) === 0) {
                return response([
                    'success' => true,
                    'message' => 'This user not have any repair',
                ], 400);
            }
            return response([
                'success' => true,
                'message' => 'Retrieving user repairs successfully',
                'data' => $repairs
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Get user repairs',
            ], 400);
        }
    }

    // New DeviceRepair
    public function newDeviceRepair(Request $request)
    {
        Log::info('New user repair');


        try {
            $userId = auth()->user()->id;
            $validator = Validator::make($request->all(), [
                'device_id' => 'required|integer',
                'imei' => 'required|integer|',
                'repair_id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], 400);
            }

            $repair = Repair::find($request->input('repair_id'));
            if (!$repair) {
                return response([
                    'success' => true,
                    'message' => "The repair_id not exist"
                ], 400);
            }
            $device = Device::find($request->input('device_id'));
            if (!$device) {
                return response([
                    'success' => true,
                    'message' => "The device_id not exist"
                ], 400);
            }
            $newUserRepair = DB::table('device_repair')
                ->insert([
                    'device_id' => $device->id,
                    'repair_id' => $repair->id,
                    'imei' => $request->input('imei'),
                    'user_id' => $userId
                ]);

            return response([
                'success' => true,
                'message' => 'Repair created succesfully',

            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Fail creating user repair',
            ], 400);
        }
    }
}
