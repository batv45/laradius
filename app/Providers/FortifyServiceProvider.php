<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::authenticateUsing(function (\Laravel\Fortify\Http\Requests\LoginRequest $request) {
            $user = User::where('email', $request->input('email'))
                ->first();
            if ($user &&
                Hash::check($request->password, $user->password)) {
//                if(!$user->hasVerifiedTC()){
//                    flash('T.C. Kimliğiniz onaylı değil.')->error();
//                    return false;
//                }
                return $user;
            }
        });

        Fortify::loginView(function () {
            return inertia('Auth/Login',[
                'sample_users' => User::with('roles')->whereIn('id',[1,2,3,4,])->get()->append('role_text')
            ]);
        });

        Fortify::registerView(function () {
            return inertia('Auth/Register');
        });

        Fortify::requestPasswordResetLinkView(function () {

            return inertia('Auth/ForgotPassword');
        });

        Fortify::resetPasswordView(function (Request $request) {


            $getToken = \DB::table('password_resets')
                ->where('email',$request->input('email'))
                ->first();

            if( !$getToken || !Hash::check($request->route()->parameter('token'),$getToken->token) ){
                alert()->error('Hata!','Şifre sıfırlama bağlantısı geçersiz.');
                return redirect()->route('login');
            }

            return inertia('Auth/ResetPassword', ['request' => [
                'email' => $request->input('email'),
                'token' => $request->route()->parameter('token')
            ]]);
        });

        Fortify::verifyEmailView(function () {
            flash('Hesabınızı onaylayın')->warning();
            return redirect()->route('profile.edit');
            return inertia('Auth/VerifyEmail');
        });

        Fortify::confirmPasswordView(function () {
            return inertia('Auth/ConfirmPassword');
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);

         Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(100)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('verification', function (Request $request) {
            if ($request->routeIs('verification.verify')){
                return Limit::perMinutes(1,3)->by($request->email.$request->ip());
            }
            return Limit::perMinutes(1,1)->by($request->email.$request->ip());
        });
    }
}
