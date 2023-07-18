<?php

namespace App\Models\Elim;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $product_id
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property int $shop_id
 * @property Shop $shop
 */
class ProductRequest extends Model
{
    protected $table = 'products_requests';

    public static $statuses = [
        'PENDING' => 0,
        'APPROVED' => 1,
        'DENY' => 2,
    ];

    public function product()
    {
        return $this->hasOne('App\Models\Elim\ProductShop', 'id', 'product_id');
    }

    public function shop()
    {
        return $this->belongsTo('App\Models\Shop', 'shop_id');
    }
}
