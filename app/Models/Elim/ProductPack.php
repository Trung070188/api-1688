<?php

namespace App\Models\Elim;

use Illuminate\Database\Eloquent\Model;

class ProductPack extends Model
{
    protected $table = 'products_packs';

    public static function findById($productId, $skuId) {
        return self::where('product_id', $productId)->where('sku', $skuId)->first();
    }
}
