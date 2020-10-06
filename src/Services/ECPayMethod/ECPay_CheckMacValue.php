<?php


namespace ThothPharaohKen\laravelGoldFlow\Services\ECPayMethod;

use ThothPharaohKen\laravelGoldFlow\Constants\ECPay_EncryptType;

class ECPay_CheckMacValue
{
    public static function generate($arParameters = array(), $HashKey = '', $HashIV = '', $encType = 0)
    {
        $sMacValue = '' ;

        if (isset($arParameters)) {
            unset($arParameters['CheckMacValue']);
            uksort($arParameters, array('\ThothPharaohKen\laravelGoldFlow\Services\ECPayMethod\ECPay_CheckMacValue','merchantSort'));
            var_dump(count($arParameters));
            // 組合字串
            $sMacValue = 'HashKey=' . $HashKey ;
            foreach ($arParameters as $key => $value) {
                $sMacValue .= '&' . $key . '=' . $value ;
            }

            $sMacValue .= '&HashIV=' . $HashIV ;

            // URL Encode編碼
            $sMacValue = urlencode($sMacValue);

            // 轉成小寫
            $sMacValue = strtolower($sMacValue);

            // 取代為與 dotNet 相符的字元
            $sMacValue = str_replace('%2d', '-', $sMacValue);
            $sMacValue = str_replace('%5f', '_', $sMacValue);
            $sMacValue = str_replace('%2e', '.', $sMacValue);
            $sMacValue = str_replace('%21', '!', $sMacValue);
            $sMacValue = str_replace('%2a', '*', $sMacValue);
            $sMacValue = str_replace('%28', '(', $sMacValue);
            $sMacValue = str_replace('%29', ')', $sMacValue);
            // 編碼
            switch ($encType) {
                case ECPay_EncryptType::ENC_SHA256:
                    // SHA256 編碼
                    $sMacValue = hash('sha256', $sMacValue);
                    break;

                case ECPay_EncryptType::ENC_MD5:
                default:
                    // MD5 編碼
                    $sMacValue = md5($sMacValue);
            }

            $sMacValue = strtoupper($sMacValue);
        }

        return $sMacValue ;
    }
    /**
     * 自訂排序使用
     */
    private static function merchantSort($a, $b)
    {
        return strcasecmp($a, $b);
    }

    /**
     * 參數內特殊字元取代
     * 傳入    $sParameters    參數
     * 傳出    $sParameters    回傳取代後變數
     */
    public static function Replace_Symbol($sParameters)
    {
        if (!empty($sParameters)) {
            $sParameters = str_replace('%2D', '-', $sParameters);
            $sParameters = str_replace('%2d', '-', $sParameters);
            $sParameters = str_replace('%5F', '_', $sParameters);
            $sParameters = str_replace('%5f', '_', $sParameters);
            $sParameters = str_replace('%2E', '.', $sParameters);
            $sParameters = str_replace('%2e', '.', $sParameters);
            $sParameters = str_replace('%21', '!', $sParameters);
            $sParameters = str_replace('%2A', '*', $sParameters);
            $sParameters = str_replace('%2a', '*', $sParameters);
            $sParameters = str_replace('%28', '(', $sParameters);
            $sParameters = str_replace('%29', ')', $sParameters);
        }

        return $sParameters ;
    }
}
