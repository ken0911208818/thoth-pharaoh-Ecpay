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
        // 發送訂單url
        $ServicePostURL = $ServiceURL . '/SP/CreateTrade';
        // 送出查詢並取回結果。
        $szResult = self::ServerPost($arParameters, $ServicePostURL);
        // 轉結果為陣列。
        $arResult = json_decode($szResult, true);
        // 重新整理回傳參數。
        foreach ($arResult as $keys => $value) {
            if ($keys == 'CheckMacValue') {
                $szCheckMacValueReturn = $value;
            } else {
                $arFeedback[$keys] = $value;
            }
        }

        if (array_key_exists('RtnCode', $arFeedback) && $arFeedback['RtnCode'] != '1') {
            array_push($arErrors, vsprintf('#%s: %s', array($arFeedback['RtnCode'], $arFeedback['RtnMsg'])));
        }

        // 參數取回壓碼驗證
        $szCheckMacValueReturnParameters = ECPay_CheckMacValue::generate($arFeedback, $HashKey, $HashIV, $arParameters['EncryptType']);

        if ($szCheckMacValueReturnParameters != $szCheckMacValueReturn) {
            array_push($arErrors, 'CheckMacValue verify fail.');
        }
        if (sizeof($arErrors) > 0) {
            throw new \Exception(join('- ', $arErrors));
        }
        // 訂單網址
        $CreditURL = $ServiceURL . '/SP/SPCheckOut?MerchantID=' . $arParameters['MerchantID'];
        $CreditURL .= '&SPToken=' . $arFeedback['SPToken'] . '&PaymentType=' . $arParameters['ChoosePayment'];
        return $CreditURL;
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
