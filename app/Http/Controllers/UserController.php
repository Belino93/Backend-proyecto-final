<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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
            ], Response::HTTP_BAD_REQUEST);
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
                ], Response::HTTP_BAD_REQUEST);
            }

            $user = User::find($userId);
            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->save();

            return response([
                "success" => true,
                'message' => 'Username updated',
                'data' => [$user->name, $user->surname]
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Update user fails',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteUser(Request $request)
    {
        Log::info('Dropping user');
        $userId = auth()->user()->id;

        try {
            $user = User::find($userId);
            if ($user->role_id === 2) {
                return response([
                    'success' => true,
                    'message' => "Admins can't be deleted"
                ], Response::HTTP_BAD_REQUEST);
            }
            $user->delete();

            return response([
                'success' => true,
                'message' => 'Profile deleted',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Fail dropping user',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // Admin can update users to admin
    public function userUpdateRole(Request $request)
    {
        Log::info('User update role');

        try {
            $isAdmin = auth()->user()->role_id;

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response([
                    'success' => false,
                    'message' => $validator->messages()
                ], Response::HTTP_BAD_REQUEST);
            }

            if ($isAdmin !== 2) {
                return response([
                    'success' => true,
                    'message' => "Only admins can do this"
                ], Response::HTTP_BAD_REQUEST);
            }
            $user = User::find($request->input('user_id'));

            if (!$user) {
                return response([
                    'success' => true,
                    'message' => 'device_id dont match'
                ], Response::HTTP_BAD_REQUEST);
            }
            $user->role_id = 2;
            $user->save();

            return response([
                'success' => true,
                'message' => 'User role updated'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            
            return response([
                'success' => false,
                'message' => 'Something went wrong updating role user',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
