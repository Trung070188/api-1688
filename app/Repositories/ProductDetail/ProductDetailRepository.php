<?php

namespace App\Repositories\ProductDetail;

use App\Events\SaveProduct1688ToDbEvent;
use App\Services\Signature;

class ProductDetailRepository
{
    public function getProductDetail($id)
    {
        $token = env('ACCESS_TOKEN_1688');
        $signature = new Signature();
        $api = 'param2/1/com.alibaba.fenxiao/cross.productInfo.get/';

        $codeArray = array(
            'access_token' => $token,
            'offerId' => $id,
            'offerUrl' => "https://detail.1688.com/offer/670272731661.htm"
        );
        $_aop_signature = $signature->genSignature($api, $codeArray);

        if ($_aop_signature) {

            $url = env('LINK_API_1688') . '/' . $api . env('APP_KEY_1688');

            $data = [
                'access_token' => $token,
                'offerId' => $id,
                'offerUrl' => 'https://detail.1688.com/offer/670272731661.htm',
                '_aop_signature' => $_aop_signature,
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            event(new SaveProduct1688ToDbEvent($result));
        }

    }
}
