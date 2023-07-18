<?php

namespace App\Jobs;

use App\Models\Elim\Attribute;
use App\Models\Elim\AttributeValue;
use App\Models\Elim\Product;
use App\Models\Elim\ProductAttribute;
use App\Services\TranslateLanguageByGoogle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveProductToDbElimJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $product;
    public function __construct($data)
    {
        $this->product = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $product = $this->product['productInfo'];
        $trans = new TranslateLanguageByGoogle();

        // check product databse elim

        $productDataElim = Product::findByProductCode($product['productID']);

        if(!$productDataElim)
        {
            $productElim = new Product();
            $productElim->product_id = $product['productID'];
            $productElim->type = 3; // 1: Sản phẩm nội địa; 2: Sản phẩm quốc tế, 3: Sản phẩm quốc tế khách tìm kiem
            $productElim->approved_request_at = date('Y-m-d H:i:s');
            $productElim->approved_at = date('Y-m-d H:i:s');
            $productElim->status = 1;
            $productElim->status_approve = 1;
            $productElim->category_id = $product['categoryID'];
            $productElim->name_cn = $product['subject'];

            // translate product_name china -> vietnam, english

            $productElim->name = $trans->translateLanguage($product['subject'], 'vi');
            $productElim->name_en = $trans->translateLanguage($product['subject'], 'en');

            $productElim->content_cn = $product['description'];

            $productElim->save();


            // save Attributes
            $translatedAttributes = [];
            foreach ($product['productAttribute'] as $attribute) {
                $translatedAttribute = [];
                $translatedAttribute['attributeID'] = $attribute['attributeID'];
                $translatedAttribute['attributeName_vi'] = $trans->translateLanguage($attribute['attributeName'], 'vi');
                $translatedAttribute['value_vi'] = $trans->translateLanguage($attribute['value'], 'vi');
                $translatedAttribute['attributeName_en'] = $trans->translateLanguage($attribute['attributeName'], 'en');
                $translatedAttribute['value_en'] = $trans->translateLanguage($attribute['value'], 'en');
                $translatedAttribute['attributeName_cn'] = $attribute['attributeName'];
                $translatedAttribute['value_cn'] = $attribute['value'];
                $translatedAttributes[] = $translatedAttribute;
            }
            foreach ($translatedAttributes as $translatedAttribute)
            {
                $att = Attribute::findById($translatedAttribute['attributeID']);
                if(!$att)
                {
                    $attribute = new Attribute();

                    $attribute->attribute_id = $translatedAttribute['attributeID'];
                    $attribute->display_name = $translatedAttribute['attributeName_vi'];
                    $attribute->name = $translatedAttribute['attributeName_vi'];
                    $attribute->name_en = $translatedAttribute['attributeName_en'];
                    $attribute->name_cn = $translatedAttribute['attributeName_cn'];

                    $attribute->save();
                }

                // save value attributes
                $valueAtt = AttributeValue::findByValue($translatedAttribute['value_cn'], $translatedAttribute['attributeID']);
                if(!$valueAtt)
                {
                    $valueAttribute = new AttributeValue();

                    $valueAttribute->attribute_id = $translatedAttribute['attributeID'];
                    $valueAttribute->value = $translatedAttribute['value_vi'];
                    $valueAttribute->value_en = $translatedAttribute['value_en'];
                    $valueAttribute->value_cn = $translatedAttribute['value_cn'];

                    $valueAttribute->save();
                }


                // save product attributes

                $productAtt = ProductAttribute::findById($product['productID'], $translatedAttribute['attributeID']);

                if(!$productAtt)
                {
                    $productAttribute = new ProductAttribute();

                    $productAttribute->product_id = $product['productID'];
                    $productAttribute->attribute_id = $translatedAttribute['attributeID'];

                    $productAttribute->save();

                }

            }

            // save product thumb
            if(isset($product['productImage']['image']))
            $thumbs = [];
            foreach ($product['productImage']['images'] as $image) {
                $url = new \stdClass();
                $url->uri = $image;
                $thumbs[] = $url;
            }
            $productElim->thumbs = $thumbs;
            if (count($thumbs) > 0) {
                $productElim->thumb = $thumbs[0];
            }
            $productElim->save();

        }

    }
}
