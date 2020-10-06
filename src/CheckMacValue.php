<?php


namespace ThothPharaohKen\laravelGoldFlow;

use ThothPharaohKen\laravelGoldFlow\Services\ECPayMethod\ECPay_CheckMacValue;

class CheckMacValue
{


    /**
     * 比較檢查碼是否相同
     * @param array $arParameters 回傳參數
     * @param int $EncryptType 1:SHA256, 0:MD5 預設為1
     */
    public static function comparison(array $arParameters, int $EncryptType = 1)
    {
        $HashIV = config('ecpay.HashIV');
        $HashKey = config('ecpay.HashKey');
        $szCheckMacValue = ECPay_CheckMacValue::generate($arParameters, $HashKey, $HashIV, $EncryptType);
        if ($arParameters['CheckMacValue'] != $szCheckMacValue) {
            return false;
        }
        return true;
    }
}
