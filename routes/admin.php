<?php
use Illuminate\Support\Facades\Route;

//region Admin Routes
Route::group(['prefix' => 'admin','as' => 'admin.'],function(){

    //region User Routes
    Route::put('user/{user}/update_department',[\App\Http\Controllers\Admin\User\UserController::class,'update_department'])
        ->name('user.update_department');

    Route::put('user/{user}/update_role',[\App\Http\Controllers\Admin\User\UserController::class,'update_role'])
        ->name('user.update_role');

    Route::put('user/{user}/update_permission',[\App\Http\Controllers\Admin\User\UserController::class,'update_permission'])
        ->name('user.update_permission');

    Route::delete('user/destroy_force/{user}',[\App\Http\Controllers\Admin\User\UserController::class,'destroy_force'])
    ->name('user.destroy_force');

    Route::delete('user/{user}/deleteProfilePhoto',[\App\Http\Controllers\Admin\User\UserController::class,'delete_profile_photo'])->name('user.profilePhotoDelete');


    Route::resource('user',\App\Http\Controllers\Admin\User\UserController::class)
    ->except('show');

    //endregion

    Route::group(['middleware' => ['auth','permission:user.show'],'prefix'=>'api','as' => 'api.'],function (){

        Route::get('users',[\App\Http\Controllers\Api\User\SearchController::class,'SearchUserWithHouse'])
            ->name('user.search');
    });

});
//endregion
