<?php

namespace ThothPharaohKen\laravelGoldFlow\Test;

use ThothPharaohKen\laravelGoldFlow\EcPay;

class EcPayTest extends BaseTestCase
{
    public function test_EcPay()
    {
        $pay = new Ecpay('http://pharaohcashapi.thoth-dev.com', 'http://pharaohcashapi.thoth-dev.com');
        $result = $pay->CVS(500, 1440)->CreateTrade();
    }
}
