<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Get all users(admin)
    public function getUsers()
    {
        Log::info('Getting user');
        try {
            $users = User::all()->toArray();
            return response([
                'success' => true,
                'message' => 'Users retrieving successfully',
                'data' => $users
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Get users fails',
            ], 400);
        }
    }

    public function updateUser(Request $request)
    {
        Log::info('Updating userName');
        $userId = auth()->user()->id;
        
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:100|string',
                'surname' => 'required|max:100|string'

            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], 400);
            }

            $user = User::find($userId);
            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->save();

            return response([
                "success"=>true,
                'message'=>'Username updated',
                'data'=> [$user->name, $user->surname]
            ], 200); 

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Update user fails',
            ], 400);
        }
    }
}
