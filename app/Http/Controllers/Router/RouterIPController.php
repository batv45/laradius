<?php

namespace App\Http\Controllers\Router;

use App\Enums\RouterIPType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Router\RouterIPCreateRequest;
use App\Models\Router;
use App\Models\RouterIP;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Inertia\Inertia;
use IPv4\SubnetCalculator;

class RouterIPController extends Controller
{
    public function index()
    {
    }

    public function create(Request $request, $router_id)
    {
        $router = Router::withTrashed()->findOrFail($router_id);

        return inertia('Router/IP/Create',[
            'page_router' => $router,
            'page_ip_types' => RouterIPType::getInstances(),
        ]);
    }

    public function store(RouterIPCreateRequest $request, $router_id)
    {
        $router = Router::withTrashed()->findOrFail($router_id);

        if( $router->ips()
            ->where('ip',$request->input('ip'))
            ->exists() )
        {
            $mess = new MessageBag(['ip' => 'IP daha önceden kayıt edilmiş.']);
            return back()
                ->withErrors($mess);
        }

        $router->ips()->create($request->validated());

        flash("Yeni {$request->input('type')} IP oluştuldu.")->success();
        return redirect()->route('router.show',$router->id);
    }

    public function show($router_id,$routerip_id)
    {
        $router = Router::withTrashed()->findOrFail($router_id);
        $routerip = $router->ips()->findOrFail($routerip_id);

        return inertia('Router/IP/Show',[
            'page_router' => $router,
            'page_port_range' => $routerip->getPortRange()
        ]);
    }

    public function edit($id)
    {
        return inertia('Router/IP/Edit');
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
