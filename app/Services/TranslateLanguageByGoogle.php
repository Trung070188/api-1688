<?php

namespace App\Services;

class TranslateLanguageByGoogle
{
    public function translateLanguage($keyword, $lang)
    {
            $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&dt=t&q=' . rawurlencode($keyword) . '&tl=' .$lang;
            $handle = curl_init($url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($handle);
            $responseDecoded = json_decode($response, true);
            curl_close($handle);
            $result = '';
            if (!empty($responseDecoded[0])) {
                foreach ($responseDecoded[0] as $responseItem) {
                    if (!empty($responseItem[0])) {
                        $result .= $responseItem[0];
                    }
                }
            }
            return $result;
    }
}
