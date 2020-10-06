<?php

namespace ThothPharaohKen\laravelGoldFlow\Test;

use ThothPharaohKen\laravelGoldFlow\CheckMacValue;
use ThothPharaohKen\laravelGoldFlow\EcPay;

class EcPayTest extends BaseTestCase
{
    public function test_CVS_EcPay()
    {
        $pay = new Ecpay('http://pharaohcashapi.thoth-dev.com', 'http://pharaohcashapi.thoth-dev.com');
        $result = $pay->CVS(500, 1440)->CreateTrade();
        $result = explode('&', $result, 3);
        $this->assertSame('PaymentType=CVS', end($result));
    }

    public function test_ATM_EcPay()
    {
        $pay = new Ecpay('http://pharaohcashapi.thoth-dev.com', 'http://pharaohcashapi.thoth-dev.com');
        $result = $pay->ATM(500, 1440)->CreateTrade();
        $result = explode('&', $result, 3);
        $this->assertSame('PaymentType=ATM', end($result));
    }

    public function test_CheckMacVAlue_EcPay()
    {
        $testvalue = [
          "RtnCode" => "1",
          "RtnMsg" => "成功",
          "SPToken" => "A8F2AAC8AD9343ADA67E8BB6539CAE72",
          "MerchantID" => "2000132",
          "MerchantTradeNo" => "thoth160195586727",
          "CheckMacValue" => "2FFA4DA0C9198AB64CA0F3886FCAE15FFE4B9A4A57E29901DB57FE66CD8973F7"
        ];
        $this->assertTrue(CheckMacValue::comparison($testvalue));
    }

    public function test_Credit_ECPay()
    {
        $pay = new Ecpay('http://pharaohcashapi.thoth-dev.com', 'http://pharaohcashapi.thoth-dev.com');
        $result = $pay->ATM(500, 1440)->CreateTrade();
        $result = explode('&', $result, 3);
        $this->assertSame('PaymentType=ATM', end($result));
    }

    public function test_inWeb_EcPay()
    {
        $pay = new Ecpay('http://pharaohcashapi.thoth-dev.com', 'http://pharaohcashapi.thoth-dev.com');
        $result = $pay->ATM(500, 1440)->CreateTrade(null, true);
        $this->assertArrayHasKey('MerchantID', $result);
        $this->assertArrayHasKey('SPToken', $result);
        $this->assertArrayHasKey('PaymentType', $result);
    }
}
