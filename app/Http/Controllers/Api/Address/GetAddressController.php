<?php

namespace App\Http\Controllers\Api\Address;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class GetAddressController extends Controller
{
    public function index()
    {
        $country = Country::where('iso_code','TR')->get();
        return response()->json($country);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
