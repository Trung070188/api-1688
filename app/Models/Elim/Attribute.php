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

    public static function findByCode($code) {
        return self::where('code', $code)->first();
    }
}
