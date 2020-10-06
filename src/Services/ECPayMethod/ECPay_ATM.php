<?php


namespace ThothPharaohKen\laravelGoldFlow\Services\ECPayMethod;

class ECPay_ATM extends ECPay_Verification
{

    /**
     * ECPay_ATM constructor.
     */
    public function __construct()
    {
    }

    public $arPayMentExtend = array(
        'ExpireDate'       => 3,
        'PaymentInfoURL'   => '',
        'ClientRedirectURL'=> '',
    );

    //過濾多餘參數
    public function filter_string($arExtend = array(), $InvoiceMark = '')
    {
        $arExtend = parent::filter_string($arExtend, $InvoiceMark);
        return $arExtend ;
    }
}
