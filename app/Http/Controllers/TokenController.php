<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;

class TokenController extends Controller
{

    public function index()
    {
        $tokens = Token::latest()->get();
        return view('tokens.index',compact('tokens'));
    }


    public function create()
    {
        return view('tokens.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $rules = [
            'title' => 'required',
            'amount' => 'required',
            'expiry_date' => 'required',
            'up_to' => 'required'

        ], $messages = [
            'title.required' => 'The Title field is required!',
            'amount.required' => 'The Amount field is required!',
            'expiry_date.required' => 'The Expiry_date field is required!',
            'up_to.required' => 'The UP to field is required!',

        ]);

        $token = Token::create([
            'title' => $request->title,
            'expiry_date' => $request->expiry_date,
            'up_to' => $request->up_to,
            'amount' => $request->amount,
            'user_id' => auth()->user()->id,

        ]);
        return redirect()->route('tokens.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $token = Token::find($id);
        return view('tokens.edit',compact('token'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $rules = [
            'title' => 'required',
            'amount' => 'required',
            'expiry_date' => 'required',
            'up_to' => 'required'

        ], $messages = [
            'title.required' => 'The Title field is required!',
            'amount.required' => 'The Amount field is required!',
            'expiry_date.required' => 'The Expiry_date field is required!',
            'up_to.required' => 'The UP to field is required!',

        ]);

        $token = Token::where('id',$id)->update([
            'title' => $request->title,
            'expiry_date' => $request->expiry_date,
            'up_to' => $request->up_to,
            'amount' => $request->amount,
            'user_id' => auth()->user()->id,

        ]);
        return redirect()->route('tokens.index');
    }

    public function delete_tokens($id)
    {
        $token = Token::find($id);
        $token->delete();
        return redirect()->route('tokens.index');
    }
}
