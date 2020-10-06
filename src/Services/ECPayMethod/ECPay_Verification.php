<?php


namespace ThothPharaohKen\laravelGoldFlow\Services\ECPayMethod;

abstract class ECPay_Verification
{
    public $arPayMentExtend = [];


    //檢查共同參數
    public function check_string($arParameters = array())
    {
        $arErrors = array();
        if (strlen($arParameters['MerchantID']) == 0) {
            array_push($arErrors, 'MerchantID is required.');
        }
        if (strlen($arParameters['MerchantID']) > 10) {
            array_push($arErrors, 'MerchantID max langth as 10.');
        }

        if (strlen($arParameters['ReturnURL']) == 0) {
            array_push($arErrors, 'ReturnURL is required.');
        }
        if (strlen($arParameters['ClientBackURL']) > 200) {
            array_push($arErrors, 'ClientBackURL max langth as 200.');
        }
        if (strlen($arParameters['OrderResultURL']) > 200) {
            array_push($arErrors, 'OrderResultURL max langth as 200.');
        }

        if (strlen($arParameters['MerchantTradeNo']) == 0) {
            array_push($arErrors, 'MerchantTradeNo is required.');
        }
        if (strlen($arParameters['MerchantTradeNo']) > 20) {
            array_push($arErrors, 'MerchantTradeNo max langth as 20.');
        }
        if (strlen($arParameters['MerchantTradeDate']) == 0) {
            array_push($arErrors, 'MerchantTradeDate is required.');
        }
        if (strlen($arParameters['TotalAmount']) == 0) {
            array_push($arErrors, 'TotalAmount is required.');
        }
        if (strlen($arParameters['TradeDesc']) == 0) {
            array_push($arErrors, 'TradeDesc is required.');
        }
        if (strlen($arParameters['TradeDesc']) > 200) {
            array_push($arErrors, 'TradeDesc max langth as 200.');
        }
        if (strlen($arParameters['ChoosePayment']) == 0) {
            array_push($arErrors, 'ChoosePayment is required.');
        }
        if (strlen($arParameters['NeedExtraPaidInfo']) == 0) {
            array_push($arErrors, 'NeedExtraPaidInfo is required.');
        }
        if (sizeof($arParameters['Items']) == 0) {
            array_push($arErrors, 'Items is required.');
        }

        // 檢查CheckMacValue加密方式
        if (strlen($arParameters['EncryptType']) > 1) {
            array_push($arErrors, 'EncryptType max langth as 1.');
        }

        if (sizeof($arErrors) > 0) {
            throw new \Exception(join('<br>', $arErrors));
        }

        if (!$arParameters['PlatformID']) {
            unset($arParameters['PlatformID']);
        }

        if ($arParameters['ChoosePayment']!=='ALL') {
            unset($arParameters['IgnorePayment']);
        }

        return $arParameters ;
    }

    //檢查延伸參數
    public function check_extend_string($arExtend = array(), $InvoiceMark = '')
    {
        //沒設定參數的話，就給預設參數
        foreach ($this->arPayMentExtend as $key => $value) {
            if (!isset($arExtend[$key])) {
                $arExtend[$key] = $value;
            }
        }

        //若有開發票，檢查一下發票參數
        if ($InvoiceMark == 'Y') {
            $arExtend = $this->check_invoiceString($arExtend);
        }
        return $arExtend ;
    }

    //檢查商品
    public function check_goods($arParameters = array())
    {
        // 檢查產品名稱。
        $szItemName = '';
        $arErrors   = array();
        if (sizeof($arParameters['Items']) > 0) {
            foreach ($arParameters['Items'] as $keys => $value) {
                $szItemName .= vsprintf('#%s %d %s x %u', $arParameters['Items'][$keys]);
                if (!array_key_exists('ItemURL', $arParameters)) {
                    if (array_key_exists('URL', $arParameters['Items'][$keys])) {
                        $arParameters['ItemURL'] = $arParameters['Items'][$keys]['URL'];
                    }
                }
            }
            if (strlen($szItemName) > 0) {
                $szItemName = mb_substr($szItemName, 1, 200);
                $arParameters['ItemName'] = $szItemName ;
            }
        } else {
            array_push($arErrors, "Goods information not found.");
        }

        if (sizeof($arErrors) > 0) {
            throw new \Exception(join('<br>', $arErrors));
        }

        unset($arParameters['Items']);
        return $arParameters ;
    }

    //過濾多餘參數
    public function filter_string($arExtend = array(), $InvoiceMark = '')
    {
        $arPayMentExtend = array_merge(array_keys($this->arPayMentExtend), ($InvoiceMark == '') ? array() : $this->arInvoice);
        foreach ($arExtend as $key => $value) {
            if (!in_array($key, $arPayMentExtend)) {
                unset($arExtend[$key]);
            }
        }

        return $arExtend ;
    }

    public function check_invoiceString(array $arExtend = [])
    {
    }
}
