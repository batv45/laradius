<?php

namespace App\Http\Controllers\Fortify;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource as UserResource;
use Inertia\Inertia;

class ProfilePhotoController extends Controller
{

    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfilePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => ['required', 'image', 'max:1024'],
        ])->setAttributeNames([
            'photo' => 'Fotoğraf dosya',

        ])->validateWithBag('updateProfilePhoto');

//        $this->checkValidation($validator);

        Auth::user()->updateProfilePhoto($request->photo);

        flash('Profil fotoğrafınız güncellendi.')->success();
        return back();
    }


    /**
     * check Validation.
     *
     * @param  ValidatorContract $validator
     */
    protected function checkValidation(ValidatorContract $validator)
    {
        if(optional($validator)->fails()){

            return response()->json([
                'status' => 'error',
                'data' => $validator->errors(),
            ], 422);
        }
    }
}
