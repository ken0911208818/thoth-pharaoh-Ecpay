<?php

namespace Pharaoh\GoldFlow;

class EcPay
{
    public function test()
    {
        dump('aad');
        return config('ecpay.HashIV');
    }
}
