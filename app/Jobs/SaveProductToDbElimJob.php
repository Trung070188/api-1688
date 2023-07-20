<?php

namespace App\Jobs;

use App\Models\Elim\Attribute;
use App\Models\Elim\AttributeValue;
use App\Models\Elim\Product;
use App\Models\Elim\ProductAttribute;
use App\Models\Elim\ProductSku;
use App\Models\Elim\ProductSkuAttribute;
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
        $product =$this->product;
        $trans = new TranslateLanguageByGoogle();

        // check product databse elim
        $productDataElim = Product::findByProductCode($product['productID']);

        if(!$productDataElim)
        {
            $productElim = new Product();
            $productElim->product_id = $product['productID'];
            $productElim->type = 2; // 1: Sản phẩm nội địa; 2: Sản phẩm quốc tế, 3: Sản phẩm quốc tế khách tìm kiem
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
                $valueAtt = AttributeValue::findByValue($translatedAttribute['attributeID']);
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

                $productAtt = ProductAttribute::findById($productElim->id, $attribute->id);

                if(!$productAtt)
                {
                    $productAttribute = new ProductAttribute();

                    $productAttribute->product_id = $productElim->id;
                    $productAttribute->attribute_id = $attribute->id;

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

            // save product sku
            if(isset($product['productSkuInfos']))
            {

                foreach ($product['productSkuInfos'] as $sku)
                {
                    $translatedSkuAttributes= [];
                    foreach ($sku['attributes'] as $transSkuAttribute)
                    {
                        $translatedAttribute = [];
                        $translatedAttribute['attributeID'] = $transSkuAttribute['attributeID'];
                        $translatedAttribute['attributeName_vi'] = $trans->translateLanguage($transSkuAttribute['attributeName'], 'vi');
                        $translatedAttribute['value_vi'] = $trans->translateLanguage($transSkuAttribute['attributeValue'], 'vi');
                        $translatedAttribute['attributeName_en'] = $trans->translateLanguage($transSkuAttribute['attributeName'], 'en');
                        $translatedAttribute['value_en'] = $trans->translateLanguage($transSkuAttribute['attributeValue'], 'en');
                        $translatedAttribute['attributeName_cn'] = $transSkuAttribute['attributeName'];
                        $translatedAttribute['value_cn'] = $transSkuAttribute['attributeValue'];
                        $translatedSkuAttributes[] = $translatedAttribute;

                    }
                    $skuNameVn = [];
                    $skuNameCn = [];
                    $skuNameEn = [];
                    foreach ($translatedSkuAttributes as $translatedSkuAttribute)
                    {
                        $skuNameVn[] = $translatedSkuAttribute['attributeName_vi'] . ' - '. $translatedSkuAttribute['value_vi'];
                        $skuNameCn[] = $translatedSkuAttribute['attributeName_cn']. ' - '. $translatedSkuAttribute['value_cn'];
                        $skuNameEn[] = $translatedSkuAttribute['attributeName_en']. ' - '. $translatedSkuAttribute['value_en'];
                    }
                    $skuDb = ProductSku::findById($productElim->id, $sku['skuId']);

                    if(!$skuDb)
                    {
                        $productSku = new ProductSku();

                        $productSku->product_id = $productElim->id;
                        $productSku->sku_id = $sku['skuId'];
                        $productSku->price_ndt = $sku['consignPrice'];
                        $productSku->image = $sku['attributes'][0]['skuImageUrl'] ?? '';
                        $productSku->name = implode(', ', $skuNameVn);
                        $productSku->name_cn = implode(', ', $skuNameCn);
                        $productSku->name_en = implode(', ', $skuNameEn);

                        $productSku->save();
                    }

                    foreach ($sku['attributes'] as $skuAttribute)
                    {
                        $skuAtt = ProductSkuAttribute::findById($productElim->id,$sku['skuId'],$skuAttribute['attributeID']);
                        if(!$skuAtt)
                        {
                            $skuProductAttribute = new ProductSkuAttribute();
                            $skuProductAttribute->product_id = $productElim->id;
                            $skuProductAttribute->product_sku_id = $sku['skuId'];
                            $skuProductAttribute->attribute_value_id = $skuAttribute['attributeID'];

                            $skuProductAttribute->save();
                        }
                    }
                }
            }

        }

    }
}
