<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\AccountCreateRequest;
use App\Http\Requests\Account\AccountUpdateRequest;
use App\Models\Account;
use App\Models\Router;
use App\Models\RouterLanip;
use App\Models\RouterWanip;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Inertia\Inertia;
use IPv4\SubnetCalculator;
use RouterOS\Query;

class AccountController extends Controller
{
    public function index()
    {
        $accs = Account::all();

        return inertia('Account/Index',[
            'page_accounts' => $accs
        ]);
    }

    public function create(Request $request)
    {
        $routers = Router::with('lanips','wanips')->get();
        $used_ips = Account::select(['router_lanip_id','router_wanip_id'])->get();

        if( $routers->count() < 1 ){
            alert('Router Bulunamadı!','Abone eklemek için önce Router oluşturmalısınız.','warning');
            return redirect()->route('router.index');
        }
        return inertia('Account/Create',[
            'page_routers' => $routers,
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

    public function store(AccountCreateRequest $request)
    {
        $router = Router::findOrFail($request->input('router_id'));
        $mess = new MessageBag();


        if( !$router->lanips()->find($request->input('router_lanip_id'))->exists() ){
            $mess->add('router_lanip_id', 'LAN IP mevcut değil.');
        }
        if( !$router->wanips()->find($request->input('router_wanip_id'))->exists() ){
            $mess->add('router_wanip_id', 'WAN IP mevcut değil.');
        }
        if( Account::where('username',$request->input('username'))->where('router_lanip_id',$request->input('router_lanip_id'))->exists()){
            $mess->add('username', "Router'da bu kullanıcı kaydı mevcut.");
        }

        if($mess->count() > 0 ){
            return back()->withInput()->withErrors($mess);
        }

        $acc = Account::create($request->validated());
        flash('Yeni abone oluşturuldu.')->success();

        return redirect()->route('account.show',$acc->id);
    }

    public function show(Request $request, $account_id)
    {
        $acc = Account::findOrFail($account_id);

        return inertia('Account/Show',[
            'page_account' => $acc,
            'page_meta' => function () use($request,$acc){
                if(inertia_get_only() === 'page_meta'){
                    try {
                        $sor = collect(\RouterOS::client()->query('/ppp/active/print')->read())->where('name', $acc->username)->first();
                    }catch ( \Exception $e){
                        $sor = [];
                        flash('Mikrotik bağlantı hatası!')->error();
                    }
                    return [
                        'connection' => $sor
                    ];
                }else
                    return (object)[];
            }
        ]);

    }

    public function edit($account_id)
    {
        $acc = Account::with('router_lanip','router_wanip')->findOrFail($account_id);

        $routers = Router::with('lanips','wanips')->get();
        $used_ips = Account::select(['router_lanip_id','router_wanip_id'])->get();

        return inertia('Account/Edit',[
            'page_account' => $acc,
            'page_routers' => $routers,
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

    public function update(AccountUpdateRequest $request, $account_id)
    {
        $acc = Account::findOrFail($account_id);

        $router = Router::findOrFail($request->input('router_id'));
        $mess = new MessageBag();


        if( !$router->lanips()->find($request->input('router_lanip_id'))->exists() ){
            $mess->add('router_lanip_id', 'LAN IP mevcut değil.');
        }
        if( !$router->wanips()->find($request->input('router_wanip_id'))->exists() ){
            $mess->add('router_wanip_id', 'WAN IP mevcut değil.');
        }
        if( Account::where('username',$request->input('username'))
            ->whereKeyNot($acc->id)
            ->exists()){
            $mess->add('username', "Router'da bu kullanıcı kaydı mevcut.");
        }

        if($mess->count() > 0 ){
            return back()->withInput()->withErrors($mess);
        }

        $acc->update($request->validated());

        return redirect()->route('account.show',$acc->id);

    }

    public function destroy($account_id)
    {
        $acc = Account::findOrFail($account_id);

        $acc->delete();

        return redirect()->route('account.index');

    }
}
