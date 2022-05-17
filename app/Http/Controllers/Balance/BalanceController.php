<?php

namespace App\Http\Controllers\Balance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Balance\BalancePaymentRequest;
use App\Models\PaymentHistory;
use App\Models\Plan;
use App\Services\Payment\Iyzico\IyzicoService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BalanceController extends Controller
{
    public function index()
    {
        $balanceHistory = auth()->user()->balanceHistory()->with('referenceable')
            ->latest()
            ->limit(10)
            ->get();

        return inertia('Balance/Index',[
            'page_balance_history' => $balanceHistory,
            'page_classes' => [
                'Plan' => Plan::class,
                'PaymentHistory' => PaymentHistory::class
            ]
        ]);
    }

    public function create(Request $request, IyzicoService $iyzipay)
    {
        return inertia('Balance/Create',[
            'page_payment_history' => auth()->user()->payment_histories()->latest()->limit(10)->get(),
            'page_classes' => [
                'Plan' => Plan::class,
                'PaymentHistory' => PaymentHistory::class
            ]
        ]);
    }

    public function store(BalancePaymentRequest $request, IyzicoService $iyzipay)
    {
        $user = $request->user();
        $payhisto = PaymentHistory::create([
                    'user_id' => $user->id,
                    'description' => 'Bakiye yüklemesi.',
                    'price' => $request->input('amount'),
                ]);

        $form = $iyzipay->runPaymentForm($payhisto->getKey(), $request->input('amount'),$user);


        if( $form->getStatus() === 'success'){
            $payhisto->update([
                'raw_result' => json_decode($form->getRawResult(),true)
            ]);
            return Inertia::location($form->getPaymentPageUrl());
        }else{
            alert('Ödeme Başlatılamadı!',$form->getErrorMessage(),'error');
        }
        return redirect()->route('balance.index');
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

    public function destroy($id)
    {
        //
    }
}
