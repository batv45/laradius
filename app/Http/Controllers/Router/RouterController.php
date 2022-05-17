<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Http\Requests\Router\RouterCreateRequest;
use App\Models\Router;
use App\Models\RouterIP;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Inertia\Inertia;

class RouterController extends Controller
{
    public function index(Request $request)
    {
        $routers = Router::withTrashed()->withCount('account')->get();

        if( inertia_get_only() == 'page_routers'){
            $routers->append('client_status');
        }

        return inertia('Router/Index',[
            'page_routers' => $routers,
            'page_routers_check' => Inertia::lazy(function() use($request) {
                $get_router_id = (int)$request->header('X-Laradius-RouterID');
                if( $get_router_id > 0 ){
                    $router = Router::findOrFail($get_router_id);
                    try {
                        sleep(4);
                        $client = $router->getRouterOS();
                        return (object)[
                            $router->id => true
                        ];
                    } catch (Exception $exception) {
                        return (object)[
                            $router->id => false
                        ];
                    }
                }
                return (object)[];
            })
        ]);
    }

    public function create()
    {
        //
    }

    public function store(RouterCreateRequest $request)
    {
        if( Router::withTrashed()
            ->where('ip',$request->input('ip'))
            ->where('port',$request->input('port'))
            ->exists() )
        {
            $mess = new MessageBag(['ip' => 'IP ve Port zaten kayıtlı.','port'=>'IP ve Port zaten kayıtlı.']);
            return back()
                    ->withErrors($mess);
        }

        $router = Router::create($request->validated());

        flash('Yeni Mikrotik oluşturuldu.')->success();
        return back();
    }

    public function show($router_id)
    {
        $router = Router::withTrashed()
            ->withCount('account','ips')
            ->with('ips')
            ->findOrFail($router_id);

        if( inertia_get_only() == 'page_router'){
            $router->append('account_active');
        }

        return inertia('Router/Show',[
            'page_router' => $router
        ]);
    }

    public function edit($router_id)
    {
        $router = Router::withTrashed()->findOrFail($router_id);

        return inertia('Router/Edit', [
            'page_router' => $router,

        ]);
    }

    public function update(RouterCreateRequest $request, $router_id)
    {
        $router = Router::withTrashed()->findOrFail($router_id);

        $router->update($request->validated());

        flash('Mikrotik bilgisi güncellendi.')->success();
        return redirect()->route('router.show',$router->id);
    }

    public function destroy(Request $request, $router_id)
    {
        $router = Router::withTrashed()->findOrFail($router_id);
        if( $router->account()->exists() ){
            alert('Silinemez!','Bu cihaza kayıtlı kullanıcılar mevcut silinemez. Bunun yerine cihazı arşive alabilirsiniz.','warning');
            return back();
        }

        if($request->hasHeader('X-Laradius-ForceDelete')){
            $router->forceDelete();
        }else{
            $router->delete();
        }

        flash('Mikrotik silindi.')->success();
        return redirect()->route('router.index');
    }
}
