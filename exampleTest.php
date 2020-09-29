<?php


use Pharaoh\GoldFlow\EcPay;
use PHPUnit\Framework\TestCase;

class exampleTest extends TestCase
{
    public function test()
    {
        $tt = new EcPay();
        var_dump($tt->test());
    }
}
