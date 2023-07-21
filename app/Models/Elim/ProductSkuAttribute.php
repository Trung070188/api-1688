<?php

namespace App\Models\Elim;

use Illuminate\Database\Eloquent\Model;

class ProductSkuAttribute extends Model
{
    protected $table = 'product_sku_attributes';

    public static function findById($productId, $skuId, $attributeValueId) {
        return self::where('product_id', $productId)->where('product_sku_id', $skuId)
            ->where('attribute_value_id', $attributeValueId)->first();
    }
}
