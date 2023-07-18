<?php

namespace App\Models\Elim;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductShop extends Model
{
    use SoftDeletes;
    protected $timestamp = true;
    protected $appends = ['image_url'];
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'name',
        'price',
        'campaign_ids',
        'from',
        'unit',
        'packs',
        'country',
        'short_description',
        'description',
        'thumb',
        'thumbs',
        'category_id',
        'province_id',
        'ship_fee_type',
        'allow_booking_time',
        'note',
        'quantity',
        'shareable',
    ];
    protected $table = 'products_shops';

    public static $shipFeeTypes = [
        [
            'id' => 0,
            'name' => 'Free ship',
        ],
        [
            'id' => 1,
            'name' => 'Trả phí ship cho shipper',
        ],
        [
            'id' => 2,
            'name' => 'Giá Ship mặc định của đơn',
        ],
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->getAttribute('image')) return NULL;
        return request()->getSchemeAndHttpHost() . '/storage/uploads/' . ($this->getAttribute('image'));
    }

    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function properties()
    {
        return $this->hasMany('App\Models\Elim\ProductProperty', 'product_id', 'id');
    }

    public function share()
    {
        return $this->hasOne('App\Models\SharedProduct', 'product_id', 'id');
    }

    public function prices()
    {
        return $this->hasMany('App\Models\Elim\ProductPrice', 'product_id', 'id');
    }

    public function productRequest()
    {
        return $this->hasOne('App\Models\Elim\ProductRequest', 'product_id', 'id');
    }
}
