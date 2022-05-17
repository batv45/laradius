<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string'
        ]);

        $users = User::where(function (Builder $query) use ($request){
            $query->where('first_name','LIKE','%'.$request->input('query').'%')
            ->orWhere('last_name','LIKE','%'.$request->input('query').'%')
            ->orWhere('email','LIKE','%'.$request->input('query').'%')
            ->orWhere('tc_kn','LIKE','%'.$request->input('query').'%');
        })->get();

        return $this->responseJsonSuccess('ok',['users'=>$users]);
    }

    public function SearchUserWithHouse(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:1'
        ]);
        $us = \App\Models\User::query();
        $us->where('first_name','LIKE', '%'.$request->input('search').'%');
        $us->orWhere('last_name','LIKE', '%'.$request->input('search').'%');
        $us->orWhere('email','LIKE', '%'.$request->input('search').'%');
        $us->orWhere('tc_kn','LIKE', '%'.$request->input('search').'%');

        $us->limit(10);
        return response()->json([
            'success' => true,
            'data' => $us->get()
        ]);
    }
}
