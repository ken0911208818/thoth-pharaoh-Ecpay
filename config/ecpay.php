<?php

return [
    'MerchantId' => env('ECPAY_MERCHANT_ID', '2000132'), //特店編號
    'HashKey' => env('ECPAY_HASH_KEY', '5294y06JbISpM5x9'),
    'HashIV' => env('ECPAY_HASH_IV', 'v77hoKGq4kWxNNIS'),
    'InvoiceHashKey' => env('ECPAY_INVOICE_HASH_KEY', '串接發票用'),
    'InvoiceHashIV' => env('ECPAY_INVOICE_HASH_IV', '串接發票用'),
    'SendForm' => env('ECPAY_SEND_FORM', null),
    'TradeDesc' => env('ECPAY_TradeDesc', '我叫商家名稱')
];
