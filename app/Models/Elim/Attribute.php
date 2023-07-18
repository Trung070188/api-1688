<?php

namespace App\Models\Elim;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attributes';

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id', 'attribute_id');
    }

    public static function findById($attribute_id) {
        return self::where('attribute_id', $attribute_id)->first();
    }
}
