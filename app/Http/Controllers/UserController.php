<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getUsers()
    {
        Log::info('Getting user');
        try {
            $users = User::all()->toArray();
            return response([
                'success'=> true,
                'message'=> 'Users retrieving successfully',
                'data'=> $users
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response([
                'success' => false,
                'message' => 'Get users fails',
            ], 400);
        }
    }
}
