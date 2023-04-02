<?php

/**
 * Created by Reliese Model
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\Collection;
use Carbon\Carbon;

/**
 *
 * Class ProductVsSeller
 *
 * @property int $product_id
 * @property int $seller_id
 * @property float $seller_price
 * @property float $seller_stock
 * @property Carbon $modified_at
 *
 * @property Product $product
 * @property Seller $seller
 * @property Collection|ProductVsSeller[] $product_vs_seller
 *
 * @package App\Models
 */

class ProductVsSeller extends Model
{
    protected $table = 'products_vs_sellers';
    public $timestamps = false;

    protected $casts = [
        'product_id' => 'int',
        'seller_id' => 'int'
    ];

    protected $dates = [
        'modified_at'
    ];

    protected $fillable = [
        'product_id',
        'seller_id',
        'seller_price',
        'seller_stock',
        'modified_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function product_vs_seller()
    {
        return $this->hasMany(ProductVsSeller::class, 'product_id','seller_id');
    }

}
