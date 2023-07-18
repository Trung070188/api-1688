<?php

namespace App\Repositories\SearchKeyword;

use App\Services\Signature;
use App\Services\TranslateLanguageByGoogle;

class SearchKeywordRepository
{
    public function ApiSearchKeyword1688($keyword, $pageNum)
    {
        $trans = new TranslateLanguageByGoogle();

        $keyword = $trans->translateLanguage($keyword, 'zh');

        $token = env('ACCESS_TOKEN_1688');
        $signature = new Signature();
        $api = 'param2/1/com.alibaba.fenxiao/cross.keywords.search/';

        $param = json_encode(array(
            "keywords" => $keyword,
            "pageSize" => 50,
            "pageNum" => $pageNum ?? 1
        ), JSON_UNESCAPED_UNICODE);

        $codeArray =  array(
            'access_token' => env('ACCESS_TOKEN_1688'),
            'scenario' => env('SCENARIO'),
            'param' => $param
        );

        $_aop_signature = $signature->genSignature($api, $codeArray);
        if ($_aop_signature) {

            $url = env('LINK_API_1688') . '/' . $api . env('APP_KEY_1688');

            $data = [
                'access_token' => $token,
                'scenario' => env('SCENARIO'),
                'param' => json_encode([
                    "keywords" => $keyword,
                    "pageSize" => 50,
                    "pageNum" => $pageNum
                ], JSON_UNESCAPED_UNICODE),
                '_aop_signature' => $_aop_signature,
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            return $result;
        }
    }
}
