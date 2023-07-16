<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TokenPayment;

class TokenPaymentController extends Controller
{
    public function token_pay(Request $request){
        dd($request->all());
    }
}
