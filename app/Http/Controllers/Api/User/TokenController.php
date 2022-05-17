<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\TokenCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class TokenController extends Controller
{
    public function create(TokenCreateRequest $request)
    {
        if( $request->user()->tokens()->where('name',$request->token_name)->exists() ){
            $mesg = new MessageBag(['token_name' => 'Token adı zaten mevcut.']);
            return $this->responseJsonError('Token adı zaten mevcut.',['errors' => $mesg->toArray()]);
        }

        $token = $request->user()->createToken($request->token_name);

        return $this->responseJsonSuccess('Token created.',[
            'token' => $token->plainTextToken
        ]);
    }

    public function destroy(Request $request,$token_id)
    {
        $token = $request->user()->tokens()->findOrFail($token_id);
        $token->delete();

        return $this->responseJsonSuccess('Token deleted.');
    }
}
