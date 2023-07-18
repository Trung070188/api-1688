<?php

namespace App\Models\Elim;

use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'value',
    ];
}
