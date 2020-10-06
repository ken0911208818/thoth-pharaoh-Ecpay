<?php


namespace ThothPharaohKen\laravelGoldFlow\Services\ECPayMethod;

class ECPay_CVS extends ECPay_Verification
{
    // 過濾多餘參數
    public function filter_string($arExtend = array(), $InvoiceMark = '')
    {
        $arExtend = parent::filter_string($arExtend, $InvoiceMark);
        return $arExtend ;
    }
    /**
     * ECPay_CVS constructor.
     */
    public function __construct()
    {
    }
}
