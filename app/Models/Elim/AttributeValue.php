<?php

namespace App\Models\Elim;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int       $id
 * @property int       $attribute_id
 * @property string    $attribute_name
 * @property string    $value
 * @property string    $value_cn
 * @property string    $value_en
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class AttributeValue extends Model
{
    protected $table = 'attribute_values';

    public static function findByValue($value, $id) {
        return self::where('value_cn', $value)->where('attribute_id', $id)->first();
    }
}

