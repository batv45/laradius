<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class MainController extends Controller
{
    public function index()
    {
        return \Redirect::route('dashboard');
    }

    public function dashboard()
    {
        $user = auth()->user();


        return inertia('Dashboard',[
            'page_last_users' => $user->hasRole('super-admin') ? User::withTrashed()->limit(10)->latest()->get()->append('role_text') : [],
            'tc_verified_users' => $user->hasRole('super-admin') ?  User::withTrashed()->whereNull('tc_verified_at')->count() : 0
        ]);
    }
}
