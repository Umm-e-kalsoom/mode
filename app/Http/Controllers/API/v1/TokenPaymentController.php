<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TokenPayment;
use App\Models\RemainingToken;
use App\Models\Discount;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

use Stripe;

class TokenPaymentController extends Controller
{
    public function token_pay(Request $request){

        Stripe\Stripe::setApiKey('sk_test_51M1CQcBXbn9BsZ0hPR23i3B0mIBWnYI9rX1woVhgMyjT81ySeRUhX3BPwUQluen4ku4ljsI2ydOpGCS5ZNqdd3BO00y60S864r');

        // $stripe = Stripe\Charge::create ([
        //         "amount" => $request->amount * 100,
        //         "currency" => "usd",
        //         "source" => $request->stripeToken,
        //         "description" => "Test payment from itsolutionstuff.com."
        // ]);


            $token = TokenPayment::create([
                'user_id'   =>  $request->user_id,
                'stripe_id' =>  $request->stripe_id,
                'tokens'    => $request->tokens,
                'amount'    => $request->amount,
            ]);

            if($token){
                $rem =RemainingToken::where('user_id',$token->user_id)->first();
                if(empty($rem)){
                    RemainingToken::create([
                        'user_id'   =>  $request->user_id,
                        'tokens'    => $request->tokens
                    ]);
                }
                else{
                    RemainingToken::where('user_id',$token->user_id)->update([
                        'tokens'    => $rem->tokens + $request->tokens
                    ]);
                }

            }
            $response['success']= 'success';
            $response['error']= null;
            $response['message']= 'Successfully';
            $response['remaining_token'] = $rem;

        return response()->json($response);

    }
    public function remain_token(Request $request){


        $rem =RemainingToken::where('user_id',$request->user_id)->first();

        if(!empty($rem)){

            $response['success']= 'success';
            $response['error']= null;
            $response['message']= 'Successfully';
            $response['remaining_token'] = $rem;
        }else{
            $response['success']= 'Failed';
            $response['error']= 'Failed to fetch data';
            $response['remaining_token'] = $rem;
        }
        return response()->json($response);
    }

    public function store_coupon(Request $request){


        $code = $request->input('code') ;
        $discount = $request->input('discount');
        $type = $request->input('type');
        $expire_at = $request->input('expire_at');
        $description = $request->input('discription');
        $user_id = auth()->user()->id;

        $statut = $request->input('statut');
        $date = date('Y-m-d H:i:s');
        if ($statut == "on") {
            $statut = "yes";
        } else {
            $statut = "no";
        }

        $discounts = new Discount;

        if ($discounts) {
            $discounts->code = $code;
            $discounts->discount = $discount;
            $discounts->type = $type;
            $discounts->expire_at = $expire_at;
            $discounts->discription = $description;
            $discounts->user_id = $user_id;

            $discounts->statut = $statut;
            $discounts->creer = $date;
            $discounts->modifier = $date;
            $discounts->save();
        }


        if(!empty($discounts)){

            $response['success']= 'success';
            $response['error']= null;
            $response['message']= 'Successfully Stored';
            $response['remaining_token'] = $discounts;
        }else{
            $response['success']= 'Failed';
            $response['error']= 'Failed to store data';
            $response['remaining_token'] = $discounts;
        }
        return response()->json($response);
    }
    public function updateDiscount(Request $request, $id){

        $validator = Validator::make($request->all(), $rules = [
            'code' => 'required',
            'discount' => 'required',
            'type' => 'required',
            'expire_at' => 'required|date',
            'discription'=>'required',
        ], $messages = [
            'code.required' => 'The Code field is required!',
            'discount.required' => 'The Discount field is required!',
            'type.required' => 'The Discount Type is required!',
            'expire_at.required' => 'The Expire date field is required!',
            'discription.required' => 'The Description field is required'

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->with(['message' => $messages])
                ->withInput();
        }


        $code = $request->input('code');
        $discount = $request->input('discount');
        $type = $request->input('type');
        $expire_at = $request->input('expire_at');
        $description = $request->input('discription');

        $statut = $request->input('statut');
        $date = date('Y-m-d H:i:s');
        if ($statut == "on") {
            $statut = "yes";
        } else {
            $statut = "no";
        }

        $discounts = Discount::find($id);

        if ($discounts) {
            $discounts->code = $code;
            $discounts->discount = $discount;
            $discounts->type = $type;
            $discounts->expire_at = $expire_at;
            $discounts->discription = $description;

            $discounts->statut = $statut;
            $discounts->modifier = $date;
            $discounts->save();
        }
        if(!empty($discounts)){

            $response['success']= 'success';
            $response['error']= null;
            $response['message']= 'Successfully update';
            $response['remaining_token'] = $discounts;
        }else{
            $response['success']= 'Failed';
            $response['error']= 'Failed to supdatetore data';
            $response['remaining_token'] = $discounts;
        }
        return response()->json($response);

    }
    public function get_coupen($id){
        $discounts = Discount::find($id);
        if(!empty($discounts)){

            $response['success']= 'success';
            $response['error']= null;
            $response['message']= 'Successfully get';
            $response['remaining_token'] = $discounts;
        }else{
            $response['success']= 'Failed';
            $response['error']= 'Failed to get data';
            $response['remaining_token'] = $discounts;
        }
        return response()->json($response);

    }
    public function coupen(Request $request,$code){
        $discounts = Discount::where('code',$code)->first();
        if(!empty($discounts)){

            $response['success']= 'success';
            $response['error']= null;
            $response['message']= 'Successfully get';
            $response['remaining_token'] = $discounts;
        }else{
            $response['success']= 'Failed';
            $response['error']= 'Coupen Not Found';
            $response['remaining_token'] = $discounts;
        }
        return response()->json($response);
    }
}
