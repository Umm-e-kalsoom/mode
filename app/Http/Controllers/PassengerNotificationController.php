<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PassengerNotificationController extends Controller
{

    public function index()
    {
        return view('notification.passenger');
    }
    public function send_notify(Request $request){
        dd($request->all());
    }
}
