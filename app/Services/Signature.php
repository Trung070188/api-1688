<?php

namespace App\Services;

class Signature
{
    public function genSignature($api, $param)
    {
        $url = 'http://gw.api.alibaba.com/openapi/param2/1/';
        $appKey = env('APP_KEY_1688');
        $appSecret = env('SIGNING_KEY');
        $apiInfo = $url . $api . $appKey . '?';

        $code_arr = array(
            'access_token=' => env('ACCESS_TOKEN_1688'),
            'scenario=' => env('SCENARIO'),
            'param=' => json_encode(array(
                "keywords" => "pencil",
                "categoryIds" => [],
                "quantityBegin" => 1,
                "priceStart" => "1",
                "priceEnd" => "100",
                "sortType" => "price",
                "sortOrder" => null,
                "filter" => ["shipIn48Hours", "powerMerchant"],
                "pageSize" => 20,
                "pageNum" => 1
            ))
        );
        $aliParams = array();
        foreach ($code_arr as $key => $val)
        {
            $aliParams[] = $key . $val;
        }
        dd($aliParams);

        sort($aliParams);
        $sign_str = join('', $aliParams);
        $sign_str = $apiInfo . $sign_str;
        $code_sign = strtoupper(bin2hex(hash_hmac("sha1", $sign_str, $appSecret, true)));
        return $code_sign;
    }

}
