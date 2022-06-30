<?php

namespace App\Http\Controllers\Hotspot;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotspot\HotspotLoginRequest;
use App\Models\Hotspot;
use App\Models\HotspotAccount;
use App\Services\Radius\RadiusService;
use DB;
use Illuminate\Http\Request;

class HotspotLoginController extends Controller
{
    public function login( $hotspot_id)
    {
        $hotspot = Hotspot::findOrFail($hotspot_id);

        return view('hotspot.login',compact('hotspot'));
    }
    public function check(HotspotLoginRequest $request, $hotspot_id)
    {
        $hotspot = Hotspot::findOrFail($hotspot_id);

        $hotacc = HotspotAccount::firstOrCreate([
            'identity_number' => $request->input('identity_number')
        ],[
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'birth_year' => $request->input('birth_year'),
        ]);


        if($hotacc){
            app(RadiusService::class)->syncHotspotAccount($hotacc);
        }

        return view('hotspot.post',[
            'username' => $hotacc->username,
            'password' => '123' // 123
        ]);


        dd($request->all(),
        $hotspot->toArray(),
        $hotacc->toArray());
    }
}
