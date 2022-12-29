<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\UserRegistered;
use Illuminate\Support\Facades\Mail;
class EmailController extends Controller
{
    public function index()
    {
        $testMailData = [
            'title' => 'Welcome to Fixapp',
            'body' => 'Register successfully'
        ];

        dd('Success! Email has been sent successfully.');
    }
}
