<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Deligoez\TCKimlikNo\Rules\TCKimlikNoVerify;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use TCKimlikNo;

/**
 * User Profile Update Classes
 */
class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        /**
         * @var User $user
         */
        Validator::make($input, [
            'tc_kn' => ['required',Rule::unique('users')->ignore($user->id),new TCKimlikNoVerify()],
            'birth_year' => ['required','numeric','digits:4','min:' . (date('Y')-100),'max:' . date('Y')],
            'first_name' => ['required', 'string', 'max:255','min:3'],
            'last_name' => ['required', 'string', 'max:255','min:2'],
            'phone' => ['nullable','numeric','digits:10',Rule::unique('users')->ignore($user->id)],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            ])->setAttributeNames([
                'first_name' => 'Ad',
                'last_name' => 'Soyad',
                'email' => 'E-posta',
                'phone' => 'Telefon numarası',
                'tc_kn' => 'TC Kimlik',
                'birth_year' => 'Doğum Tarihi'
        ])->setCustomMessages([
            'birth_year.date_format' => 'Doğum tarihi hatalı.'
        ])->validateWithBag('updateProfileInformation');

        $user->forceFill([
            'phone' => $input['phone']
        ]);

        if( !$user->hasVerifiedTC() ){
            $user->forceFill([
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name']
            ]);
            $tcsor = TCKimlikNo::validate(
                $input['tc_kn'],
                $input['first_name'],
                $input['last_name'],
                $input['birth_year']
            );
            if (!$tcsor){
                flash('TC Kimlik bilginiz doğrulanmadı!')->error();
            }else{
                flash('TC Kimlik bilginiz doğrulandı.')->success();
            }
            $user->forceFill([
                'tc_kn' => $input['tc_kn'],
                'birth_year' => $input['birth_year'],
                'tc_verified_at' => $tcsor?now():null,
            ]);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail)
        {
            $user->forceFill([
                'phone' => $input['phone'],
                'email' => $input['email'],
                'email_verified_at' => null
            ])->save();
            $user->sendEmailVerificationNotification();
        } else {
            $user->save();
        }

        flash('Hesap bilgileri güncellendi.')->success();

    }
}
