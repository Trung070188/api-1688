<?php

namespace App\Models\Elim;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $slug
 * @property int $price
 * @property int $price_market
 * @property int $price_discount
 * @property int $category_id
 * @property string $from
 * @property string $unit
 * @property string $country
 * @property string $thumb
 * @property string $thumbs
 * @property string $packs
 * @property \DateTime $updated_at
 * @property \DateTime $created_at
 * @property int $status
 * @property int $status_approve
 * @property \DateTime $approved_request_at
 * @property \DateTime $approved_at
 * @property int $suggested
 * @property string $content
 * @property int $menu_id
 * @property string $summary
 * @property string $category_ids
 * @property \DateTime $start_time
 * @property \DateTime $end_time
 * @property int $allow_booking_time
 * @property string $note
 * @property int $quantity
 * @property int $province_id
 * @property int $ship_fee_type
 * @property int $saleable
 * @property string $province_ids
 * @property \DateTime $deleted_at
 * @property int $ship_fee
 * @property int $order
 * @property string $shipment_note
 * @property int $fast_shipping
 * @property int $fast_shipping_fee
 * @property int $shareable
 * @property int $shop_id
 * @property int $number_sold
 * @property int $min_purchase
 * @property \DateTime $discount_end_at
 * @property \DateTime $discount_start_at
 * @property int $countdown_show
 * @property int $product_id
 * @property string $countdown_label
 * @property string $shopee_id
 * @property int $type
 */
class Product extends Model
{
    use SoftDeletes;
    const STATUS_APPROVE_NEW = 0;
    const STATUS_APPROVE_PENDING = 2;
    const STATUS_APPROVE_OK = 1;
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
        'price_discount',
        'code',
        'product_id',
        'weight',
        'size',
        'ship_quocte_nktd',
        'ship_quocte_nktm'

    ];

    protected $casts = [
        'thumb' => 'array',
        'front_image' => 'array',
        'back_image' => 'array',
        'thumbs' => 'array',
        'video' => 'array',
        'product_custom' => 'array',
    ];

    const TYPE_NORMAL = 'normal';
    const TYPE_INTERNATIONAL = 'international';

    public static $listTypeMapValue = [
        self::TYPE_NORMAL => 1,
        self::TYPE_INTERNATIONAL => 2,
    ];

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

    public function save(array $options = [])
    {
        if (!$this->slug) {
            $this->slug = Str::slug($this->name) . '-'.uniqid();
        }

        return parent::save($options); // TODO: Change the autogenerated stub
    }

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

    public function packs()
    {
        return $this->hasMany('App\Models\Elim\ProductPack', 'product_id', 'id');
    }

    public function shop()
    {
        return $this->hasOne('App\Models\Shop', 'id', 'shop_id');
    }
    public static function getProductCountByCategory($categoryId) {
        return self::query()
            ->where('status', 1)
            ->whereRaw(' FIND_IN_SET(?, category_id)', [$categoryId])
            ->count();
    }

    public static function findByProductCode($product_id) {
        return self::query()
            ->where('product_id', $product_id)
            ->where('status', 1)
            ->first();
    }

    public static function decodeFile($attachment, $defaultValue = null) {
        if (is_array($attachment)) {
            return $attachment;
        }

        if (is_string($attachment)) {
            $firstDecode = json_decode($attachment, true);

            if (is_array($firstDecode)) {
                return $firstDecode;
            }

            if (is_string($firstDecode)) {
                return json_decode($firstDecode, true);
            }
        }

        return $defaultValue;

        $decodeAtt = [];
        if (is_string($attachment)) {
            $decodeAtt = json_decode($attachment);
            if (is_string($decodeAtt)) {
                $decodeAtt = json_decode($decodeAtt);
            }
        } else {
            $decodeAtt = $attachment;
        }

        return $decodeAtt;
    }
}
