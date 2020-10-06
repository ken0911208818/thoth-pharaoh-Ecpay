# thoth-pharaoh-Ecpay
###### tags: `laravel` `綠界`
## 系統需求

- php >=7.2

## 安裝

 ```bash
composer require thoth-pharaoh-ken/laravel-goldflow
```

## 環境設定

```bash
php artisan vendor:publish --tag=ecpay
```

## env設定檔
```dotenv
ECPAY_MERCHANT_ID=2000132
ECPAY_HASH_KEY=5294y06JbISpM5x9
ECPAY_HASH_IV=v77hoKGq4kWxNNIS
ECPAY_INVOICE_HASH_KEY="串接發票用"
ECPAY_INVOICE_HASH_IV="串接發票用"
ECPAY_TradeDesc="我叫商家名稱"
```

## 用法 
- 建立超商條碼訂單
```
->CVS('金額','超商繳費截止時間')
```
    超商繳費截止時間(單位分鐘) 預設1440分鐘 = 一天
```php
use ThothPharaohKen\laravelGoldFlow\EcPay;

class XXX 
{
    public function __construct(EcPay $ecpay)
    {
        $this->ecpay = $ecpay;
    }
    
    public function send()
    {
        //有三種方法可選用 CVS, ATM, Credit
        return $this->ecpay->CVS(500, 1440)->CreateTrade();
    }

}
```

- 建立ATM訂單
```
->ATM('金額','允許繳費有效天數')
```
    超商繳費截止時間(單位天數) 預設1天
```php
use ThothPharaohKen\laravelGoldFlow\EcPay;

class XXX 
{
    public function __construct(EcPay $ecpay)
    {
        $this->ecpay = $ecpay;
    }
    
    public function send()
    {
        //有三種方法可選用 CVS, ATM, Credit
        return $this->ecpay->ATM(500, 1)->CreateTrade();
    }

}
```

- 建立信用卡訂單
```
->Credit('金額','刷卡分期期數。')
EX:3,6,12,18,24
預設是不分期
```
    刷卡分期期數(單位期數) 預設不帶
```php
use ThothPharaohKen\laravelGoldFlow\EcPay;

class XXX 
{
    public function __construct(EcPay $ecpay)
    {
        $this->ecpay = $ecpay;
    }
    
    public function send()
    {
        //有三種方法可選用 CVS, ATM, Credit
        return $this->ecpay->Creidt(500)->CreateTrade();
    }

}
```

## 訂單編號
- 用法
```
->CreateTrade('訂單編號','inWeb')
訂單編號規則與綠界相同
```
    訂單編號預設系統自產
```php
use ThothPharaohKen\laravelGoldFlow\EcPay;

class XXX 
{
    public function __construct(EcPay $ecpay)
    {
        $this->ecpay = $ecpay;
    }
    
    public function send()
    {
        //有三種方法可選用 CVS, ATM, Credit
        return $this->ecpay->Creidt(500)->CreateTrade('thothken123456789');
    }

}
```

## 付款樣式

- 另開視窗

    function 最後會回傳付款網址
    ```
    https://payment-stage.ecpay.com.tw/SP/SPCheckOut?MerchantID=2000132&SPToken=15DECC9A6BBD475DBD6DB0BD2398F212&PaymentType=CVS
    ```
- js樣板
    - 用法
    ```
    ->CreateTrade('訂單編號','inWeb')
    inWeb :bool 
    預設false
    ```
    ```php
    use ThothPharaohKen\laravelGoldFlow\EcPay;
    
    class XXX 
    {
        public function __construct(EcPay $ecpay)
        {
            $this->ecpay = $ecpay;
        }
        
        public function send()
        {
            //有三種方法可選用 CVS, ATM, Credit
            return $this->ecpay->Creidt(500)->CreateTrade(null, true);
        }
    
    }
    ```
 - 回傳
 ```php
[
  "MerchantID" => "2000132"
  "SPToken" => "97885BE649034B4DA8377C5FB29BA81B"
  "PaymentType" => "ATM"
]
```

- 套入js樣板
```html
<script src="https://payment-stage.ecpay.com.tw/Scripts/SP/ECPayPayment_1.0.0.js"
 data-MerchantID="2000132"
 data-SPToken="97885BE649034B4DA8377C5FB29BA81B"
 data-PaymentType="ATM"
 data-PaymentName="測試付款"
 data-CustomerBtn="0" >
</script>
```
[js套版](https://github.com/ken0911208818/thoth-pharaoh-Ecpay/blob/master/js套版.png)

## 安全碼檢查

- 用法 
```php
    use ThothPharaohKen\laravelGoldFlow\CheckMacValue;
    
    class XXX 
    {        
        public function check()
        {
            $testvalue = [
                      "RtnCode" => "1",
                      "RtnMsg" => "成功",
                      "SPToken" => "A8F2AAC8AD9343ADA67E8BB6539CAE72",
                      "MerchantID" => "2000132",
                      "MerchantTradeNo" => "thoth160195586727",
                      "CheckMacValue" => "2FFA4DA0C9198AB64CA0F3886FCAE15FFE4B9A4A57E29901DB57FE66CD8973F7"
                    ];
            return CheckMacValue::comparison($testvalue);// true or false
        }
    
    }
```
    