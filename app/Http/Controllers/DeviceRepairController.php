<?php

namespace App\Http\Controllers;

use App\Mail\UserRepair;
use App\Models\Device;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DeviceRepairController extends Controller
{
    // Get all deviceRepair ()
    public function getUserRepairs(Request $request)
    {
        Log::info('Getting user repairs');
        try {
            $userId = auth()->user()->id;
            $repairs = DB::table('device_repair')
                ->join('devices', 'device_repair.device_id', '=', 'devices.id')
                ->join('repairs', 'device_repair.repair_id', '=', 'repairs.id')
                ->join('states', 'device_repair.state_id', '=', 'states.id')
                ->select('device_repair.id', 'devices.brand', 'devices.model', 'repairs.type', 'device_repair.imei', 'states.name', 'device_repair.created_at', 'device_repair.updated_at')
                ->where('user_id', '=', $userId)
                ->orderBy('device_repair.id', 'desc')
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

    public function getAllUserRepairByImei(Request $request)
    {
        Log::info('Getting all user repairs by Imei');
        try {
            $userId = auth()->user()->id;
            $validator = Validator::make($request->all(), [
                'imei' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], 400);
            }
            $repairs = DB::table('device_repair')
                ->join('devices', 'device_repair.device_id', '=', 'devices.id')
                ->join('repairs', 'device_repair.repair_id', '=', 'repairs.id')
                ->join('states', 'device_repair.state_id', '=', 'states.id')
                ->select('device_repair.id', 'devices.brand', 'devices.model', 'repairs.type', 'device_repair.imei', 'states.name', 'device_repair.created_at', 'device_repair.updated_at')
                ->where('user_id', '=', $userId)
                ->where('device_repair.imei', 'like', '%'.$request->input('imei').'%')
                ->orderBy('device_repair.id', 'desc')
                ->get();
            if(!$repairs){
                return response([
                    'success' => true,
                    'message' => 'no matches'
                ], 400);
            }
            return response([
                'success' => true,
                'message' => 'Retrieving by imei successfully',
                'data' => $repairs
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Fail retrieving by imei',
            ], 400);
        }
    }


    public function getAllUsersRepairs()
    {
        Log::info('Getting all repairs');
        try {
            $userRole = auth()->user()->role_id;
            if ($userRole !== 2) {
                return response([
                    'success' => true,
                    'message' => "Not authorized"
                ], 400);
            }
            $repairs = DB::table('device_repair')
                ->join('devices', 'device_repair.device_id', '=', 'devices.id')
                ->join('repairs', 'device_repair.repair_id', '=', 'repairs.id')
                ->join('states', 'device_repair.state_id', '=', 'states.id')
                ->join('users', 'device_repair.user_id', '=', 'users.id')
                ->select('devices.brand', 'devices.model', 'repairs.type', 'device_repair.imei', 'states.name', 'users.email', 'device_repair.created_at', 'device_repair.updated_at')
                ->orderBy('device_repair.id', 'desc')
                ->get();
            return response([
                'success' => true,
                'message' => 'Retrieving all repairs successfully',
                'data' => $repairs
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Fail creating user repair',
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
                'repair_id' => 'required|integer',
                'description' => 'string'
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
            DB::table('device_repair')
                ->insert([
                    'device_id' => $device->id,
                    'repair_id' => $repair->id,
                    'imei' => $request->input('imei'),
                    'user_id' => $userId,
                    'description' => $request->input('description'),
                    'created_at' => date("Y-m-d") . (' ') . date("h:i:s")
                ]);
            // $userRepairData = DB::table('device_repair')
            //     ->join('devices', 'device_repair.device_id', '=', 'devices.id')
            //     ->join('repairs', 'device_repair.repair_id', '=', 'repairs.id')
            //     ->join('states', 'device_repair.state_id', '=', 'states.id')
            //     ->select('devices.brand', 'devices.model', 'repairs.type', 'device_repair.imei', 'states.name', 'device_repair.description')
            //     ->where('user_id', '=', $userId)
            //     ->latest('device_repair.id')
            //     ->limit(1)
            //     ->get();
            // $mailData = [
            //     'title' => 'Welcome to Fixapp',
            //     'userRepair' => $userRepairData
            // ];
            // Mail::to(auth()->user()->email)->send(new UserRepair($mailData));

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

    //Next state (admin)
    public function nextRepairState(Request $request)
    {
        Log::info('User repair updated to next state');
        try {
            $userRoleId = auth()->user()->role_id;
            $validator = Validator::make($request->all(), [
                'device_repair_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], 400);
            }
            if ($userRoleId !== 2) {
                return response([
                    'success' => true,
                    'message' => 'Only admin can do this'
                ], 400);
            }

            $user_repair = DB::table('device_repair')
                ->where('id', '=', $request->input('device_repair_id'))
                ->get();

            if ($user_repair[0]->state_id === 6) {
                return response([
                    'success' => true,
                    'message' => 'The repair have the last state'
                ]);
            }

            DB::table('device_repair')
                ->where('id', '=', $request->input('device_repair_id'))
                ->update(['state_id' => $user_repair[0]->state_id + 1, 'updated_at' => date("Y-m-d") . (' ') . date("h:i:s")]);

            return response([
                'success' => true,
                'message' => 'Repair updated to next state successfully',
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong updating state',
            ], 400);
        }
    }

    //Prev state (admin)
    public function prevRepairState(Request $request)
    {
        Log::info('User repair updated to prev state');
        try {
            $userRoleId = auth()->user()->role_id;
            $validator = Validator::make($request->all(), [
                'device_repair_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], 400);
            }
            if ($userRoleId !== 2) {
                return response([
                    'success' => true,
                    'message' => 'Only admin can do this'
                ], 400);
            }

            $user_repair = DB::table('device_repair')
                ->where('id', '=', $request->input('device_repair_id'))
                ->get();

            if ($user_repair[0]->state_id === 1) {
                return response([
                    'success' => true,
                    'message' => 'The repair have the first state'
                ]);
            }

            DB::table('device_repair')
                ->where('id', '=', $request->input('device_repair_id'))
                ->update(['state_id' => $user_repair[0]->state_id - 1, 'updated_at' => date("Y-m-d") . (' ') . date("h:i:s")]);

            return response([
                'success' => true,
                'message' => 'Repair updated to prev state successfully',
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong updating state',
            ], 400);
        }
    }

    // Update user_repair
    public function updateUserRepair(Request $request)
    {
        Log::info('Updating user repair');
        try {
            $userRoleId = auth()->user()->role_id;
            $validator = Validator::make($request->all(), [
                'device_repair_id' => 'required|integer',
                'device_id' => 'required|integer',
                'imei' => 'required|integer|',
                'repair_id' => 'required|integer',
                'description' => 'string'
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], 400);
            }
            if ($userRoleId !== 2) {
                return response([
                    'success' => true,
                    'message' => 'Only admin can do this'
                ], 400);
            }
            DB::table('device_repair')
                ->where('id', '=', $request->input('device_repair_id'))
                ->update([
                    'device_id' => $request->input('device_id'),
                    'imei' => $request->input('imei'),
                    'repair_id' => $request->input('repair_id'),
                    'description' => $request->input('description')
                ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong updating user repair',
            ], 400);
        }
    }
}
