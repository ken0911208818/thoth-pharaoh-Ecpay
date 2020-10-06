<?php


namespace ThothPharaohKen\laravelGoldFlow\Services;

use ThothPharaohKen\laravelGoldFlow\Services\ECPayMethod\ECPay_ATM;
use ThothPharaohKen\laravelGoldFlow\Services\ECPayMethod\ECPay_CheckMacValue;
use ThothPharaohKen\laravelGoldFlow\Services\ECPayMethod\ECPay_CVS;

class ECPay_CreateTrade extends ECPay_Aio
{
    //付款方式物件
    public static $PaymentObj ;

    public static function CheckOut(array $arParameters, array $arExtend, string $HashKey, string $HashIV, string $ServiceURL)
    {
        $arErrors = [];
        $arFeedback = [];
        $szCheckMacValueReturn = '' ;

        $arParameters = self::process($arParameters, $arExtend);

        //產生檢查碼
        $szCheckMacValue = ECPay_CheckMacValue::generate($arParameters, $HashKey, $HashIV, $arParameters['EncryptType']);
        $arParameters["CheckMacValue"] = $szCheckMacValue;
        // 送出查詢並取回結果。
        $szResult = self::ServerPost($arParameters, $ServiceURL);
        // 轉結果為陣列。
        $arResult = json_decode($szResult, true);
        dd($arResult);
    }

    protected static function process(array $arParameters = [], array $arExtend = [])
    {
        //宣告付款方式物件
        // $PaymentMethod    = 'ECPay_'.$arParameters['ChoosePayment'];
        // self::$PaymentObj = new $PaymentMethod;

        switch ($arParameters['ChoosePayment']) {
            case 'CVS':
                self::$PaymentObj = new ECPay_CVS();
                break;
            case 'ATM':
                self::$PaymentObj = new ECPay_ATM();
                break;
            default:
                throw new \InvalidArgumentException('尚未提供這種方法。');
        }

        //檢查參數
        $arParameters = self::$PaymentObj->check_string($arParameters);

        //檢查商品
        $arParameters = self::$PaymentObj->check_goods($arParameters);

        //檢查各付款方式的額外參數&電子發票參數
        $arExtend = self::$PaymentObj->check_extend_string($arExtend, $arParameters['InvoiceMark']);

        //過濾
        $arExtend = self::$PaymentObj->filter_string($arExtend, $arParameters['InvoiceMark']);

        //合併共同參數及延伸參數
        return array_merge($arParameters, $arExtend);
    }
}
