<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Http\Requests\Router\RouterWanIPCreateRequest;
use App\Models\Router;
use App\Models\RouterWanip;
use Illuminate\Http\Request;

class RouterWanIPController extends Controller
{
    public function index()
    {
        //
    }

    public function create($router_id)
    {
        $router = Router::withTrashed()->findOrFail($router_id);

        return inertia('Router/WanIP/Create',[
            'page_router' => $router
        ]);
    }

    public function store(RouterWanIPCreateRequest $request, $router_id)
    {
        $router = Router::withTrashed()->findOrFail($router_id);
        $ranges = get_port_ranges($request->input('account_limit'));
        $ranges->transform(function ($item, $key)use($router_id,$request) {
            return array_merge(['router_id'=>$router_id,'ip_address'=>$request->input('ip_address')],$item);
        });

        $router->wanips()->insertOrIgnore($ranges->toArray());
        flash('Yeni WAN IP eklendi.')->success();

        return redirect()->route('router.show', $router_id);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request, $router_id,$wanip_id)
    {
        $router = Router::withTrashed()->findOrFail($router_id);
        $wanip = RouterWanip::findOrFail($wanip_id);

        $wanip->where('ip_address',$wanip->ip_address)->delete();

        flash('WAN IP Bilgisi silindi.')->success();
        return redirect()->route('router.show',$router_id);
    }
}
