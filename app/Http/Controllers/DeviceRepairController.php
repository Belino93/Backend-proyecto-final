<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeviceRepairController extends Controller
{
    // Get all deviceRepair (admin)
    public function getUserRepairs(Request $request)
    {
        Log::info('Getting user repairs');
        try {
            $userId = auth()->user()->id;
            $repairs = DB::table('device_repair')
                ->join('devices', 'device_repair.device_id', '=', 'devices.id' )
                ->join('repairs', 'device_repair.repair_id', '=', 'repairs.id' )
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
    }
}
