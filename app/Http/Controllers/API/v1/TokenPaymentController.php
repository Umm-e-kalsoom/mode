<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TokenPayment;
use App\Models\RemainingToken;
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
}
