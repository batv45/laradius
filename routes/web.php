<?php

use App\Http\Controllers\Fortify\ProfilePhotoController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

//region Main Page Controller
Route::group(['middleware' => ['auth']],function(){
    Route::get('/',[\App\Http\Controllers\MainController::class,'index'])
        ->name('index');
    Route::get('/dashboard', [\App\Http\Controllers\MainController::class,'dashboard'])
        ->name('dashboard');
});
//endregion

//region Accounts & Routers
Route::group(['middleware' => ['auth']], function () {
    // Account
    Route::resource('account',\App\Http\Controllers\Account\AccountController::class);

    // Router
    Route::resource('router',\App\Http\Controllers\Router\RouterController::class);
    Route::resource('router.lanip',\App\Http\Controllers\Router\RouterLanIPController::class);
    Route::resource('router.wanip',\App\Http\Controllers\Router\RouterWanIPController::class);
});
//endregion

//region User Profile & Balance
Route::group(['middleware' => ['auth']], function () {
    // User & Profile...
    Route::get('/user/profile', [UserProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::delete('/user/profile/delete-profile-photo', [UserProfileController::class, 'deleteProfilePhoto'])
        ->name('profile.delete-profile-photo');

//    Route::delete('/user/profile/delete-user', [UserProfileController::class, 'deleteUser'])
//        ->name('profile.delete-user');

    Route::delete('/user/profile/logout-other-browser-sessions', [UserProfileController::class, 'logoutOtherBrowserSessions'])
        ->name('profile.logout-other-browser-sessions');

    Route::get('notifications/{notif}/mark-as-read',[\App\Http\Controllers\User\NotificationController::class,'markAsRead'])
        ->name('notifications.mark-as-read');

    Route::get('notifications/mark-all-read',[\App\Http\Controllers\User\NotificationController::class,'markAllRead'])

        ->name('notifications.mark-all-read');
    Route::get('notifications/delete-all',[\App\Http\Controllers\User\NotificationController::class,'deleteAll'])
        ->name('notifications.delete-all');

    Route::put('/user/settings', [UserProfileController::class, 'settingsupdate'])
        ->name('user-settings.update');

    // Balance
    Route::resource('balance',\App\Http\Controllers\Balance\BalanceController::class);
});
//endregion Routes



//region Hotspots
Route::group([], function () {
    // Account
    Route::post('hotspot/{hotspot}',[\App\Http\Controllers\Hotspot\HotspotLoginController::class,'login'])->name('hotspot.login');
    Route::post('hotspot/{hotspot}/check',[\App\Http\Controllers\Hotspot\HotspotLoginController::class,'check'])->name('hotspot.check');
});
//endregion


// Override Fortify route
Route::group(['middleware' => config('fortify.middleware')], function () {
    // Profile Information...
    Route::put('/user/profile-information', [\Laravel\Fortify\Http\Controllers\ProfileInformationController::class, 'update'])
        ->middleware(['auth'])
        ->name('user-profile-information.update');

    Route::put('/user/profile-photo', [ProfilePhotoController::class, 'updateProfilePhoto'])
        ->middleware(['auth'])
        ->name('user-profile-photo.update');
});

Route::get('addBalance',function (){

    $user = auth()->user();
    $user->increaseBalance(20,'TEST bakiyesi');

    return back();
})->name('addBalance');
Route::get('test',function (\Illuminate\Http\Request $request){

    $user = auth()->user();



    return dd((new \App\Models\RouterIPAddress)->getTable());
});

Route::get('add-notification', function() {
    broadcast(new \App\Events\NotificationEvent);
    return 'Bildirim Gönderildi.';
});
