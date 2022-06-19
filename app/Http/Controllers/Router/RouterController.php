<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Http\Requests\Router\RouterCreateRequest;
use App\Models\Account;
use App\Models\Router;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RouterController extends Controller
{
    public function index(Request $request)
    {
        $routers = Router::withTrashed()->get();

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
        return redirect()->route('router.index');
    }

    public function store(RouterCreateRequest $request)
    {
        $router = Router::create($request->validated());

        flash('Yeni Mikrotik oluşturuldu.')->success();
        return back();
    }

    public function show($router_id)
    {
        $router = Router::withTrashed()
            ->with('lanips','wanips')
            ->findOrFail($router_id);
        $used_ips = Account::select(['router_lanip_id','router_wanip_id'])->get();

        if( inertia_get_only() == 'page_router'){
            $router->append('account_active');
        }

        return inertia('Router/Show',[
            'page_router' => $router,
            'page_used_ips' => [
                'lan' => $used_ips->map(function ($item){
                    return $item['router_lanip_id'];
                }),
                'wan' => $used_ips->map(function ($item){
                    return $item['router_wanip_id'];
                })
            ]
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
