<?php

namespace App\Services\Payment\Iyzico;

use App\Models\User;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Iyzipay\Model\BasketItem;
use function Symfony\Component\Translation\t;

class IyzicoService
{

    private $apiKey;
    private $secretKey;
    private $baseUrl = "https://sandbox-api.iyzipay.com";
    protected $options;
    protected $payRequest;

    public function __construct($apiKey,$secretKey,$baseUrl)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
        $this->baseUrl = $baseUrl;

        $this->options = new \Iyzipay\Options();
        $this->reloadOptions();
    }
    private function reloadOptions(){
        $this->options->setApiKey($this->apiKey);
        $this->options->setSecretKey($this->secretKey);
        $this->options->setBaseUrl($this->baseUrl);
    }

    /**
     * @param int $paymentHistoryID
     * @param int $amount
     * @param User $user
     * @return \Iyzipay\Model\CheckoutFormInitialize
     */
    public function runPaymentForm($paymentHistoryID, $amount, User $user)
    {
        $this->payRequest = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $this->payRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $this->payRequest->setConversationId("4561");
        $this->payRequest->setPaidPrice($amount);
        $this->payRequest->setBasketId($paymentHistoryID);
        $this->payRequest->setConversationId($paymentHistoryID);
        $this->payRequest->setCurrency(\Iyzipay\Model\Currency::TL);
        $this->payRequest->setEnabledInstallments(array(2, 3, 6, 9));
        $this->payRequest->setCallbackUrl(\URL::route('payment.callback'));

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($user->id);
        $buyer->setName($user->first_name);
        $buyer->setSurname($user->last_name);
        $buyer->setGsmNumber($user->phone ?? '+905300000000');
        $buyer->setEmail("email@email.com");
        $buyer->setIdentityNumber($user->tc_kn);
        $buyer->setLastLoginDate($user->last_login_at->toDateTimeString());
        $buyer->setRegistrationDate($user->created_at->toDateTimeString());
        $buyer->setRegistrationAddress("manisa soma");
        $buyer->setIp(request()->getClientIp());
        $buyer->setCity("Manisa");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("45500");
        $this->payRequest->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($user->full_name);
        $shippingAddress->setCity("Manisa");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress("manisa soma");
        $shippingAddress->setZipCode("45500");
        $this->payRequest->setShippingAddress($shippingAddress);
        $this->payRequest->setBillingAddress($shippingAddress);

        $basketItems = collect();
        $firstBasketItem = new \Iyzipay\Model\BasketItem();
        $firstBasketItem->setId("BI001");
        $firstBasketItem->setName("Balance Payment");
        $firstBasketItem->setCategory1("User Balance");
        $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $firstBasketItem->setPrice($amount);
        $basketItems->add($firstBasketItem);
        $this->payRequest->setBasketItems($basketItems->toArray());
        $this->payRequest->setPrice($basketItems->sum(function (BasketItem $item){
            return $item->getPrice();
        }));

        return \Iyzipay\Model\CheckoutFormInitialize::create($this->payRequest, $this->options);
    }
    /**
     * @param array $data
     * @param User $user
     * @return \Iyzipay\Model\Payment
     */
    public function runPayment(array $data, User $user)
    {
        $date = Carbon::createFromFormat('m/y',$data['date']);

        $this->payRequest = new \Iyzipay\Request\CreatePaymentRequest();
        $this->payRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $this->payRequest->setConversationId("4561");
        $this->payRequest->setPaidPrice($data['amount']);
        $this->payRequest->setCurrency(\Iyzipay\Model\Currency::TL);
        $this->payRequest->setInstallment(1);
        $this->payRequest->setBasketId("R0012");
        $this->payRequest->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $this->payRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::SUBSCRIPTION);

        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setCardHolderName($data['name']);
        $paymentCard->setCardNumber($data['number']);
        $paymentCard->setExpireMonth($date->month);
        $paymentCard->setExpireYear($date->year);
        $paymentCard->setCvc($data['cvc']);
        $paymentCard->setRegisterCard(0);
        $this->payRequest->setPaymentCard($paymentCard);

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($user->id);
        $buyer->setName($user->first_name);
        $buyer->setSurname($user->last_name);
        $buyer->setGsmNumber($user->phone ?? '+905300000000');
        $buyer->setEmail("email@email.com");
        $buyer->setIdentityNumber($user->tc_kn);
        $buyer->setLastLoginDate($user->last_login_at->toDateTimeString());
        $buyer->setRegistrationDate($user->created_at->toDateTimeString());
        $buyer->setRegistrationAddress("manisa soma");
        $buyer->setIp(request()->getClientIp());
        $buyer->setCity("Manisa");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("45500");
        $this->payRequest->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($user->full_name);
        $shippingAddress->setCity("Manisa");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress("manisa soma");
        $shippingAddress->setZipCode("45500");
        $this->payRequest->setShippingAddress($shippingAddress);
        $this->payRequest->setBillingAddress($shippingAddress);

        $basketItems = collect();
        $firstBasketItem = new \Iyzipay\Model\BasketItem();
        $firstBasketItem->setId("BI001");
        $firstBasketItem->setName("Balance Payment");
        $firstBasketItem->setCategory1("User Balance");
        $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $firstBasketItem->setPrice($data['amount']);
        $basketItems->add($firstBasketItem);
        $this->payRequest->setBasketItems($basketItems->toArray());
        $this->payRequest->setPrice($basketItems->sum(function (BasketItem $item){
            return $item->getPrice();
        }));

        return \Iyzipay\Model\Payment::create($this->payRequest, $this->options);
    }

    /**
     * @param array $data
     * @param User $user
     * @return \Iyzipay\Model\Payment
     */
    public function runPayment3D(array $data, User $user)
    {
        $date = Carbon::createFromFormat('m/y',$data['date']);

        $this->payRequest = new \Iyzipay\Request\CreatePaymentRequest();
        $this->payRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $this->payRequest->setConversationId("4561");
        $this->payRequest->setPaidPrice($data['amount']);
        $this->payRequest->setCurrency(\Iyzipay\Model\Currency::TL);
        $this->payRequest->setInstallment(1);
        $this->payRequest->setBasketId("R0012");
        $this->payRequest->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $this->payRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::SUBSCRIPTION);
        $this->payRequest->setCallbackUrl(route('payment.callback'));
        $this->payRequest->setInstallment(1);

        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setCardHolderName($data['name']);
        $paymentCard->setCardNumber($data['number']);
        $paymentCard->setExpireMonth($date->month);
        $paymentCard->setExpireYear($date->year);
        $paymentCard->setCvc($data['cvc']);
        $paymentCard->setRegisterCard(0);
        $this->payRequest->setPaymentCard($paymentCard);

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($user->id);
        $buyer->setName($user->first_name);
        $buyer->setSurname($user->last_name);
        $buyer->setGsmNumber($user->phone ?? '+905300000000');
        $buyer->setEmail("email@email.com");
        $buyer->setIdentityNumber($user->tc_kn);
        $buyer->setLastLoginDate($user->last_login_at->toDateTimeString());
        $buyer->setRegistrationDate($user->created_at->toDateTimeString());
        $buyer->setRegistrationAddress("manisa soma");
        $buyer->setIp(request()->getClientIp());
        $buyer->setCity("Manisa");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("45500");
        $this->payRequest->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($user->full_name);
        $shippingAddress->setCity("Manisa");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress("manisa soma");
        $shippingAddress->setZipCode("45500");
        $this->payRequest->setShippingAddress($shippingAddress);
        $this->payRequest->setBillingAddress($shippingAddress);

        $basketItems = collect();
        $firstBasketItem = new \Iyzipay\Model\BasketItem();
        $firstBasketItem->setId("BI001");
        $firstBasketItem->setName("Balance Payment");
        $firstBasketItem->setCategory1("User Balance");
        $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $firstBasketItem->setPrice($data['amount']);
        $basketItems->add($firstBasketItem);
        $this->payRequest->setBasketItems($basketItems->toArray());
        $this->payRequest->setPrice($basketItems->sum(function (BasketItem $item){
            return $item->getPrice();
        }));

        return \Iyzipay\Model\Payment::create($this->payRequest, $this->options);
    }

    /**
     * @param int|string $binNumber
     * @param int|string $price
     * @param $conversationId
     * @return \Iyzipay\Model\InstallmentInfo
     */
    public function runInstallment($binNumber, $price, $conversationId = null)
    {
        $request = new \Iyzipay\Request\RetrieveInstallmentInfoRequest();

        //$request->setLocale(\Iyzipay\Model\Locale::TR);
        if( $conversationId )
            $request->setConversationId($conversationId);
        $request->setBinNumber((int)substr($binNumber,0,6));
        $request->setPrice($price);

        return \Iyzipay\Model\InstallmentInfo::retrieve($request, $this->options);
    }



    public function getOptions()
    {
        return $this->options;
    }
}
