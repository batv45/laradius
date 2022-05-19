<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Http\Requests\Router\RouterLanIPCreateRequest;
use App\Models\Router;
use App\Models\RouterLanip;
use Illuminate\Http\Request;

class RouterLanIPController extends Controller
{
    public function index()
    {
        //
    }

    public function create($router_id)
    {
        $router = Router::withTrashed()->findOrFail($router_id);

        return inertia('Router/LanIP/Create',[
            'page_router' => $router
        ]);
    }
    private function ip_range($start, $end) {
        $start = ip2long($start);
        $end = ip2long($end);
        return array_map('long2ip', range($start, $end) );
    }
    public function store(RouterLanIPCreateRequest $request, $router_id)
    {
        $router = Router::withTrashed()->findOrFail($router_id);
        $explode = explode('-',$request->input('ip_address'));
        $ip_collection = collect();
        $ip_collection->push(['router_id' => $router_id, 'ip_address' => $explode[0]]);
        $ipson = (int)(explode('.',$explode[0]))[3];

        if( count($explode) === 2 && $ipson < $explode[1] ){
            if( $explode[1] > 255 )
                $explode[1] = 254;
            for ($i = 1; $i <= $explode[1]-$ipson; $i++) {
                $ip_collection->push(['router_id' => $router_id,'ip_address' => long2ip( ip2long($explode[0])+$i )]);
            }
        }
        $router->lanips()->insertOrIgnore($ip_collection->toArray());
        flash('Yeni LAN IP eklendi.')->success();

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

    public function destroy($router_id, $lanip_id)
    {
        $router = Router::withTrashed()->findOrFail($router_id);
        $lanip = RouterLanip::findOrFail($lanip_id);
        sleep(2);
//        $lanip->delete();
        flash('LAN IP Bilgisi silindi.')->success();
        return redirect()->route('router.show',$router_id);
    }
}
