<?php

namespace App\Models\Elim;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    protected $table = 'product_skus';

    public static function findById($productId, $skuId) {
        return self::where('product_id', $productId)->where('sku_id', $skuId)->first();
    }

    public function attributes() {
        return $this->hasMany(ProductSkuAttribute::class, 'product_sku_id');
    }
}
