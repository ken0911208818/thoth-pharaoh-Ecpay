<?php


namespace ThothPharaohKen\laravelGoldFlow\Constants;

/**
 * 電子發票開立註記。
 */
abstract class ECPay_InvoiceState
{
    /**
     * 需要開立電子發票。
     */
    const Yes = 'Y';

    /**
     * 不需要開立電子發票。
     */
    const No = '';
}
