<?php


namespace ThothPharaohKen\laravelGoldFlow;

use Illuminate\Support\ServiceProvider;

class GoldFlowServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 合併套件設定檔
        $this->mergeConfigFrom(
            __DIR__ . '/../config/ecpay.php',
            'ecpay'
        );
    }

    public function register()
    {
    }
}
