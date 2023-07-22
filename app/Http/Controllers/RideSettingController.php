<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RideSetting;
use Illuminate\Support\Facades\Validator;

class RideSettingController extends Controller
{
    public function index(){
        $setting = RideSetting::latest()->first();
        return view('ride_setting.index',compact('setting'));
    }
    public function store(Request $request){

        $validator = Validator::make($request->all() ,$rules = [
            'token_price' => 'required|numeric|not_in:0',
            'ride_token' => 'required|numeric|not_in:0',
            'passenger_more' => 'required|numeric|not_in:0',
            'luggage_more' => 'required|numeric|not_in:0',
            'pet_more' => 'required|numeric|not_in:0',
            'package_more' => 'required|numeric|not_in:0',

        ],  $messages = [
          'token_price.required' => 'The token_price field is required! and Not 0',
          'ride_token.required' => 'The ride_token field is required! and Not 0',
          'passenger_more.required' => 'The passenger_more field is required! and Not 0',
          'luggage_more.required' => 'The luggage_more field is required! and Not 0',
          'pet_more.required' => 'The pet_more field is required! and Not 0',
          'package_more.required' => 'The package_more field is required! and Not 0',
        ]);

        if ($validator->fails()) {
        return redirect()->back()
                    ->withErrors($validator)->with(['message' => $messages])
                    ->withInput();
        }
        if(empty($request->id)){
            $settings =  RideSetting::create([
                'token_price' => $request->token_price,
                'ride_token' => $request->ride_token,
                'passenger_more' => $request->passenger_more,
                'luggage_more' => $request->luggage_more,
                'pet_more' => $request->pet_more,
                'package_more' => $request->package_more,
            ]);
        }
        else{
            $settings =  RideSetting::where('id',$request->id)->update([
                'token_price' => $request->token_price,
                'ride_token' => $request->ride_token,
                'passenger_more' => $request->passenger_more,
                'luggage_more' => $request->luggage_more,
                'pet_more' => $request->pet_more,
                'package_more' => $request->package_more,
            ]);
        }
        return redirect()->back()->with('Successfully Added');

    }
}
