<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentHistory;
use App\Services\Payment\Iyzico\IyzicoService;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    public function index(Request $request, IyzicoService $iyzipay)
    {
        $requestIyzico = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $requestIyzico->setLocale(\Iyzipay\Model\Locale::TR);
        $requestIyzico->setToken($request->input('token'));
        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($requestIyzico, $iyzipay->getOptions());

        \Log::info('PaymentForm Result: '.$request->input('token'),json_decode($checkoutForm->getRawResult(),true));

        if ($checkoutForm->getPaymentStatus() == 'SUCCESS') {
            $payH = PaymentHistory::find($checkoutForm->getBasketId());
            if (!$payH) {
                \Log::critical('PaymentHistoryID Not Found: #'.$checkoutForm->getBasketId(),json_decode($checkoutForm->getRawResult(),true));
                return abort(404, 'PaymentHistory is not found.');
            }
            \DB::transaction(function ()use ($request,$payH,$checkoutForm){
                $payH->update([
                    'result_payment_id' => $checkoutForm->getPaymentId(),
                    'paymented_at' => now(),
                    'raw_result' => json_decode($checkoutForm->getRawResult()),
                ]);
                $payH->user->increaseBalance($payH->price,'Online bakiye yüklemesi.',$payH);
            });
            $payH->user->syncBalanceHistory();
            \Log::info('Payment Successfully: #'.$payH->getKey(),$payH->toArray());
            flash('Ödeme başarılı')->success();
        }else{
            flash('Ödeme alınamadı')->error();
        }
        return redirect()->route('balance.index');
    }
}
