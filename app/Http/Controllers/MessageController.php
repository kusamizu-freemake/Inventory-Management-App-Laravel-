<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function showMessage()
    {
        $message = 'Hello, Laravel!';
        return view('welcome', ['message' => $message]);
    }
}
