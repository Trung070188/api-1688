<?php

namespace App\Services;

class Signature
{
    public function genSignature($api, $codeArray)
    {
        $appKey = env('APP_KEY_1688');
        $appSecret = env('SIGNING_KEY');
        $apiInfo =$api . $appKey;

        $code_arr = $codeArray;
        $aliParams = array();
        foreach ($code_arr as $key => $val)
        {
            $aliParams[] = $key . $val;
        }

        sort($aliParams);
        $sign_str = join('', $aliParams);
        $sign_str = $apiInfo . $sign_str;
        $code_sign = strtoupper(bin2hex(hash_hmac("sha1", $sign_str, $appSecret, true)));
        return $code_sign;
    }

}
