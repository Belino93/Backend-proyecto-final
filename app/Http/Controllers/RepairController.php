<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RepairController extends Controller
{
    //Get All repairs
    public function getAllRepairs()
    {
        Log::info('Retrieving devices');
        try {
            $repairs = Repair::all();

            return response([
                'success' => true,
                'message' => 'All devices retrieved successfully',
                'data' => $repairs,
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong retrieving repairs',
            ], 400);
        }
    }

    // New repair(type)
    public function newRepair(Request $request)
    {
        Log::info('Creating new device');
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], 400);
            }
            $repair = new Repair;
            $repair->type = $request->input('type');
            $repair->save();

            return response([
                'success' => true,
                'message' => 'Device created successfully',
                'data' => $repair
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong creating repair',
            ], 400);
        }
    }

    // Update repair
    public function updateRepair(Request $request)
    {
        Log::info('Updating repair');
        try {
            $validator = Validator::make($request->all(), [
                'repair_id' => 'required|integer',
                'type' => 'required|string|max:255'
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
                    'message' => 'repair_id dont match'
                ], 400);
            }

            $repair->type = $request->input('type');
            $repair->save();
            return response([
                'success' => true,
                'message' => 'Device updated'
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong updating repair',
            ], 400);
        }
    }
    public function deleteRepair(Request $request)
    {
        Log::info('Deleting repair');
        try {
            $validator = Validator::make($request->all(), [
                'repair_id' => 'required|integer',
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
                    'message' => 'repair_id dont match'
                ], 400);
            }
            $repair->delete();

            return response([
                'success' => true,
                'message' => 'Device dropped succesfully'
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response([
                'success' => false,
                'message' => 'Something went wrong dropping repair',
            ], 400);
        }
    }
}
