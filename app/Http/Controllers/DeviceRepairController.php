<?php

namespace App\Http\Controllers;

use App\Mail\UserRepair;
use App\Models\Device;
use App\Models\Repair;
use Http\Message\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DeviceRepairController extends Controller
{
    const EMPTY_ARRAY = 0;
    const ROLE_ADMIN = 2;
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
                ->select('device_repair.id', 'devices.brand', 'devices.model', 'repairs.type', 'device_repair.imei', 'states.name as status', 'device_repair.created_at', 'device_repair.updated_at', "device_repair.user_id")
                ->where('device_repair.user_id', '=', $userId)
                ->orderBy('device_repair.id', 'desc')
                ->get();
            if (count($repairs) === self::EMPTY_ARRAY) {
                return response([
                    'success' => true,
                    'message' => 'This user does not have any repair',
                ], Response::HTTP_BAD_REQUEST);
            }

            return response([
                'success' => true,
                'message' => 'Retrieving user repairs successfully',
                'data' => $repairs
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Get user repairs',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getAllUserRepairByImei(Request $request)
    {
        Log::info('Getting all user repairs by Imei');

        try {
            $userId = auth()->user()->id;
            $userRole = auth()->user()->role_id;
            $validator = Validator::make($request->all(), [
                'imei' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], Response::HTTP_BAD_REQUEST);
            }
            if($userRole !== self::ROLE_ADMIN){
                $repairs = DB::table('device_repair')
                ->join('devices', 'device_repair.device_id', '=', 'devices.id')
                ->join('repairs', 'device_repair.repair_id', '=', 'repairs.id')
                ->join('states', 'device_repair.state_id', '=', 'states.id')
                ->select('device_repair.id', 'devices.brand', 'devices.model', 'repairs.type', 'device_repair.imei', 'states.name', 'device_repair.created_at', 'device_repair.updated_at')
                ->where('user_id', '=', $userId)
                ->where('device_repair.imei', 'like', '%' . $request->input('imei') . '%')
                ->orderBy('device_repair.id', 'desc')
                ->get();
            }elseif ($userRole === self::ROLE_ADMIN) {
                $repairs = DB::table('device_repair')
                ->join('devices', 'device_repair.device_id', '=', 'devices.id')
                ->join('repairs', 'device_repair.repair_id', '=', 'repairs.id')
                ->join('states', 'device_repair.state_id', '=', 'states.id')
                ->select('device_repair.id', 'devices.brand', 'devices.model', 'repairs.type', 'device_repair.imei', 'states.name', 'device_repair.created_at', 'device_repair.updated_at')
                ->where('device_repair.imei', 'like', '%' . $request->input('imei') . '%')
                ->orderBy('device_repair.id', 'desc')
                ->get();
            }
            

            if (!$repairs) {
                return response([
                    'success' => true,
                    'message' => 'no matches'
                ], Response::HTTP_BAD_REQUEST);
            }

            return response([
                'success' => true,
                'message' => 'Retrieving by imei successfully',
                'data' => $repairs
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Fail retrieving by imei',
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    public function getAllUsersRepairs()
    {
        Log::info('Getting all repairs');
        try {
            $userRole = auth()->user()->role_id;
            if ($userRole !== self::ROLE_ADMIN) {
                return response([
                    'success' => true,
                    'message' => "Not authorized"
                ], Response::HTTP_BAD_REQUEST);
            }
            $repairs = DB::table('device_repair')
                ->join('devices', 'device_repair.device_id', '=', 'devices.id')
                ->join('repairs', 'device_repair.repair_id', '=', 'repairs.id')
                ->join('states', 'device_repair.state_id', '=', 'states.id')
                ->join('users', 'device_repair.user_id', '=', 'users.id')
                ->select('device_repair.id', 'devices.brand', 'devices.model', 'repairs.type', 'device_repair.imei', 'states.name', 'users.email', 'device_repair.created_at', 'device_repair.updated_at')
                ->orderBy('device_repair.id', 'desc')
                ->get();

            return response([
                'success' => true,
                'message' => 'Retrieving all repairs successfully',
                'data' => $repairs
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Fail creating user repair',
            ], Response::HTTP_BAD_REQUEST);
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
                ], Response::HTTP_BAD_REQUEST);
            }

            $repair = Repair::find($request->input('repair_id'));
            if (!$repair) {
                return response([
                    'success' => true,
                    'message' => "The repair_id not exist"
                ], Response::HTTP_BAD_REQUEST);
            }
            $device = Device::find($request->input('device_id'));
            if (!$device) {
                return response([
                    'success' => true,
                    'message' => "The device_id not exist"
                ], Response::HTTP_BAD_REQUEST);
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
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Fail creating user repair',
            ], Response::HTTP_BAD_REQUEST);
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
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($userRoleId !== 2) {
                return response([
                    'success' => true,
                    'message' => 'Only admin can do this'
                ], Response::HTTP_BAD_REQUEST);
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
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Something went wrong updating state',
            ], Response::HTTP_BAD_REQUEST);
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
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($userRoleId !== 2) {
                return response([
                    'success' => true,
                    'message' => 'Only admin can do this'
                ], Response::HTTP_BAD_REQUEST);
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
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Something went wrong updating state',
            ], Response::HTTP_BAD_REQUEST);
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
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($userRoleId !== 2) {
                return response([
                    'success' => true,
                    'message' => 'Only admin can do this'
                ], Response::HTTP_BAD_REQUEST);
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
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
