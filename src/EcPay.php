<?php

namespace ThothPharaohKen\laravelGoldFlow;

use ThothPharaohKen\laravelGoldFlow\Constants\ECPay_EncryptType;
use ThothPharaohKen\laravelGoldFlow\Constants\ECPay_ExtraPaymentInfo;
use ThothPharaohKen\laravelGoldFlow\Constants\ECPay_InvoiceState;
use ThothPharaohKen\laravelGoldFlow\Constants\ECPay_PaymentMethod;
use ThothPharaohKen\laravelGoldFlow\Constants\ECPay_PaymentMethodItem;
use ThothPharaohKen\laravelGoldFlow\Services\ECPay_CreateTrade;
use ThothPharaohKen\laravelGoldFlow\Services\EcPaySerices;
use ThothPharaohKen\laravelGoldFlow\Services\EcPayServices;

class EcPay implements allInterface
{
    protected $ServiceURL = 'ServiceURL';
    protected $HashKey = 'HashKey';
    protected $HashIV = 'HashIV';
    protected $MerchantID = 'MerchantID';
    public $ServiceMethod = 'ServiceMethod';
    private $PaymentType = 'aio';
    public $Send = [];
    public $SendExtend = 'SendExtend';
    public $Query = 'Query';
    public $Action = 'Action';
    public $EncryptType = ECPay_EncryptType::ENC_MD5;

    /**
     * EcPay constructor.
     * @param string $returnURL 付款完成回傳資訊
     * @param string $paymentInfoURL 回傳付款相關資訊
     */
    public function __construct(string $returnURL, string $paymentInfoURL)
    {
        date_default_timezone_set('Asia/Taipei');
        if (config('app.env') == 'production') {
            $this->ServiceURL = 'https://payment.ecpay.com.tw';
        } else {
            $this->ServiceURL = 'https://payment-stage.ecpay.com.tw';
        }
        $this->MerchantID = config('ecpay.MerchantId');
        $this->HashIV = config('ecpay.HashIV');
        $this->HashKey = config('ecpay.HashKey');
        $this->EncryptType = ECPay_EncryptType::ENC_SHA256;
        $this->Send = [
            "ReturnURL"         => '',  //*
            "ClientBackURL"     => '',
            "OrderResultURL"    => '',
            "MerchantTradeNo"   => '',
            "MerchantTradeDate" => date('Y/m/d H:i:s'),
            "PaymentType"       => 'aio',
            "TotalAmount"       => '',
            "TradeDesc"         => config('ecpay.TradeDesc'),
            "ChoosePayment"     => ECPay_PaymentMethod::ALL,
            "Remark"            => '',
            "ChooseSubPayment"  => ECPay_PaymentMethodItem::None,
            "NeedExtraPaidInfo" => ECPay_ExtraPaymentInfo::No,
            "DeviceSource"      => '',
            "IgnorePayment"     => '',
            "PlatformID"        => '',
            "InvoiceMark"       => ECPay_InvoiceState::No,
            "Items"             => [],
            "StoreID"           => '',
            "CustomField1"      => '',
            "CustomField2"      => '',
            "CustomField3"      => '',
            "CustomField4"      => '',
            'HoldTradeAMT'      => 0,
            'ReturnURL'         => $returnURL,
            'PaymentInfoURL'    => $paymentInfoURL
        ];
        $this->SendExtend = array();

        $this->Query = array(
            'MerchantTradeNo' => '',
            'TimeStamp' => ''
        );
//        $this->Action = array(
//            'MerchantTradeNo' => '',
//            'TradeNo' => '',
//            'Action' => ECPay_ActionType::C,
//            'TotalAmount' => 0
//        );
        $this->Capture = array(
            'MerchantTradeNo' => '',
            'CaptureAMT' => 0,
            'UserRefundAMT' => 0,
            'PlatformID' => ''
        );

        $this->TradeNo = array(
            'DateType' => '',
            'BeginDate' => '',
            'EndDate' => '',
            'MediaFormated' => ''
        );

        $this->Trade = array(
            'CreditRefundId' => '',
            'CreditAmount' => '',
            'CreditCheckCode' => ''
        );

        $this->Funding = array(
            "PayDateType" => '',
            "StartDate" => '',
            "EndDate" => ''
        );
    }

    public function CreateTrade(string $no = null)
    {
        if ($no == null) {
            $no = 'thoth' . strtotime("now") . rand(0, 100);
        }
        array_push($this->Send['Items'], [
            "Name" => config('ecpay.TradeDesc'),
            "Price" => $this->Send['TotalAmount'],
            "Currency" => "點",
            "Quantity" => 1
        ]);
        $this->Send['MerchantTradeNo'] = $no;
        $arParameters = array_merge(array('MerchantID' => $this->MerchantID, 'EncryptType' => $this->EncryptType), $this->Send);
        return $arFeedback = ECPay_CreateTrade::CheckOut($arParameters, $this->SendExtend, $this->HashKey, $this->HashIV, $this->ServiceURL);
    }

    /**
     * @param int $price 存款點數
     * @param int $storeExpireDate 超商代碼繳費時間 單位(分鐘)
     * @return $this
     */
    public function CVS(int $price, int $storeExpireDate = 1440)
    {
        $this->Send['TotalAmount'] = $price;
        $this->Send['StoreExpireDate'] = $storeExpireDate;
        $this->Send['ChoosePayment'] = ECPay_PaymentMethod::CVS;
        return $this;
    }

    /**
     * @param int $price 存款點數
     * @param int $expireDate ATM轉帳 單位(天數)
     * @return $this
     */
    public function ATM(int $price, int $expireDate = 1)
    {
        $this->Send['TotalAmount'] = $price;
        $this->Send['ExpireDate'] = $expireDate;
        $this->Send['ChoosePayment'] = ECPay_PaymentMethod::ATM;
        return $this;
    }

    public static function payment(string $payment)
    {
    }
}
