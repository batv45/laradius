<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\AccountCreateRequest;
use App\Models\Account;
use App\Models\Router;
use App\Models\RouterIP;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Inertia\Inertia;
use IPv4\SubnetCalculator;
use RouterOS\Query;
use function Clue\StreamFilter\fun;

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
        $used_ips = Account::select(['lan_ip','wan_ip','wan_port_min','wan_port_max'])->get();
        $used_ports = $used_ips->map(function($acc){
            return ['min'=> $acc->wan_port_min,'max'=>$acc->wan_port_max];
        });

        $routers = Router::with('ips')->get();
        $routers->each(function(Router $rot){
            $rot->ips->append(['usable_ips','port_range']);
        });

        if( $routers->count() < 1 ){
            alert('Router Bulunamadı!','Abone eklemek için önce Router oluşturmalısınız.','warning');
            return redirect()->route('router.index');
        }

        return inertia('Account/Create',[
            'page_routers' => $routers,
            'page_used_lan_ips' => $used_ips->unique('lan_ip')->pluck('lan_ip'),
            'page_used_wan_ips' => $used_ips->unique('wan_ip')->pluck('wan_ip'),
            'page_used_ports' => $used_ports
        ]);
    }

    public function store(AccountCreateRequest $request)
    {
        $lan = RouterIP::findOrFail($request->input('lan_subnet'));
        $wan = RouterIP::findOrFail($request->input('wan_subnet'));
        $mess = new MessageBag();

        if( !$lan->isIPAddressInSubnet($request->input('lan_ip')) ){
            $mess->add('lan_ip', 'LAN IP seçili blok için uygun değil.');
        }
        if( !$wan->isIPAddressInSubnet($request->input('wan_ip')) ){
            $mess->add('wan_ip', 'WAN IP seçili blok için uygun değil.');
        }
        if( Account::where('username',$request->input('username'))->where('router_id',$request->input('router_id'))->exists()){
            $mess->add('username', "Router'da bu kullanıcı kaydı mevcut.");
        }

        if($mess->count() > 0 ){
            return back()->withInput()->withErrors($mess);
        }

        $acc = Account::create(array_merge($request->validated(),[
            'wan_port_min' => $request->wan_port['min'],
            'wan_port_max' => $request->wan_port['max'],
        ]));
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
        $acc = Account::findOrFail($account_id);
        return inertia('Account/Edit',[
            'page_account' => $acc
        ]);
    }

    public function update(AccountCreateRequest $request, $account_id)
    {
        $acc = Account::findOrFail($account_id);

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
