<?php


namespace App\Models\Elim;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int       $id
 * @property int       $product_id
 * @property int       $attribute_id
 * @property string    $attribute_name
 * @property string    $value
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class ProductAttribute extends Model
{
    protected $table = 'products_attributes';

    public static function findById($productId, $attributeId) {
        return self::where('product_id', $productId)->where('attribute_id', $attributeId)->first();
    }
}
