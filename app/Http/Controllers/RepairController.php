<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            
            return response([
                'success' => false,
                'message' => 'Something went wrong retrieving repairs',
            ], Response::HTTP_BAD_REQUEST);
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
                ], Response::HTTP_BAD_REQUEST);
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
            ], Response::HTTP_BAD_REQUEST);
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
                ], Response::HTTP_BAD_REQUEST);
            }
            $repair = Repair::find($request->input('repair_id'));

            if (!$repair) {
                return response([
                    'success' => true,
                    'message' => 'repair_id dont match'
                ], Response::HTTP_BAD_REQUEST);
            }

            $repair->type = $request->input('type');
            $repair->save();

            return response([
                'success' => true,
                'message' => 'Device updated'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Something went wrong updating repair',
            ], Response::HTTP_BAD_REQUEST);
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
                ], Response::HTTP_BAD_REQUEST);
            }
            $repair = Repair::find($request->input('repair_id'));

            if (!$repair) {
                return response([
                    'success' => true,
                    'message' => 'repair_id dont match'
                ], Response::HTTP_BAD_REQUEST);
            }
            $repair->delete();

            return response([
                'success' => true,
                'message' => 'Device dropped succesfully'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            
            return response([
                'success' => false,
                'message' => 'Something went wrong dropping repair',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
