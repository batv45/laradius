<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(\Laravel\Fortify\Http\Requests\LoginRequest $request)
    {

        $user = User::where('tc_kn', $request->input('tc_kn'))->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response(['status'=>false,'message'=>'The entered information is incorrect.'],401);
        }

        if(!$user->hasVerifiedTC()){
            return response(['status'=>false,'message'=>'T.C. Kimliğiniz onaylı değil.'],401);
        }

        //create token
        $token = $user->createToken('login_token')->plainTextToken;

        return $this->responseJsonSuccess('Login successful!',[
            'user'=>$user,
            'token'=>$token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->responseJsonSuccess('Logout successfully');
    }
}
