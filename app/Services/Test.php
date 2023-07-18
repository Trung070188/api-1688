<?php

namespace App\Services;

class Test
{
    public function test($data)
    {
        $trans = new TranslateLanguageByGoogle();
        $product = $data['productInfo'];
        $thumbs = [];
        foreach ($product['productImage']['images'] as $image) {
            $url = new \stdClass();
            $url->uri = $image;
            $thumbs[] = $url;
        }
        dd($thumbs);

        $translatedAttributes = [];
        foreach ($product['productAttribute'] as $attribute) {
            $translatedAttribute = [];
            $translatedAttribute['attributeName'] = $trans->translateLanguage($attribute['attributeName'], 'vi');
            $translatedAttribute['value'] = $trans->translateLanguage($attribute['value'], 'vi');
            $translatedAttribute['attributeName_en'] = $trans->translateLanguage($attribute['attributeName'], 'en');
            $translatedAttribute['value_en'] = $trans->translateLanguage($attribute['value'], 'en');
            $translatedAttributes[] = $translatedAttribute;
        }
        dd($translatedAttributes);


    }
    private function attributeTranslateMap($productAttributeData = []) {
        $trans = new TranslateLanguageByGoogle();
        $attributeTransVn = $attributeTransEn = [];

        foreach ($productAttributeData as $key =>  $attribute) {
            $attributeTextTrans = $attribute['attributeName'] . ": \n";
            $attributeTextTranValues = implode("\n+ ", $attribute['value']);

            if (strlen($attributeTextTranValues) > 5000) { // Nếu quá ~5500 kí tự thì ko dịch được
                $attributeTextTrans = [$attributeTextTrans];
                $currentKey = 0;

                foreach ($attribute['value'] as $key1 => $attrValue) {
                    if (strlen($attributeTextTrans[$currentKey]) + strlen($attrValue) > 5000) {
                        ++$currentKey;
                        $attributeTextTrans[$currentKey] = '';
                    }

                    if ($key1 != 0) {
                        $attributeTextTrans[$currentKey] .= "\n+ ";
                    }

                    $attributeTextTrans[$currentKey] .= $attrValue;

                }

                $textTransVn = [];
                $textTransEn = [];

                foreach ($attributeTextTrans as $textTrans) {
                    $textTransVn[] = $trans->translateLanguage($textTrans, 'vi');
                    $textTransEn[] = $trans->translateLanguage($textTrans, 'en');
                }

                $attributeTransVn[] = implode("\n", $textTransVn);
                $attributeTransEn[] = implode("\n", $textTransEn);
            } else {
                $attributeTextTrans .= $attributeTextTranValues;

                $attributeTransVn[] = $trans->translateLanguage($attributeTextTrans, 'vi');
                $attributeTransEn[] = $trans->translateLanguage($attributeTextTrans, 'en');
            }
        }

        $attributeTransVnMap = $attributeTransEnMap = [];

        foreach ($attributeTransVn as $index => $item) {
            $idx = strpos($item, ':');

            $attributeTransVnMap[] = [
                'name' => substr($item, 0, (int) $idx),
                'values' => explode('+', substr($item, (int) $idx + 1))
            ];
        }

        foreach ($attributeTransEn as $index => $item) {
            $idx = strpos($item, ':');

            $attributeTransEnMap[] = [
                'name' => substr($item, 0, (int) $idx),
                'values' => explode('+', substr($item, (int) $idx + 1))
            ];
        }

        return [
            $attributeTransVnMap,
            $attributeTransEnMap
        ];
    }

}
