<?php


namespace ThothPharaohKen\laravelGoldFlow\Services;

abstract class ECPay_Aio
{
    protected static function ServerPost($parameters, $ServiceURL)
    {
        $ch = curl_init();

        if (false === $ch) {
            throw new \Exception('curl failed to initialize');
        }

        curl_setopt($ch, CURLOPT_URL, $ServiceURL);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        $rs = curl_exec($ch);

        if (false === $rs) {
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }

        curl_close($ch);

        return $rs;
    }
}
