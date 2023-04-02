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
 * Class Product
 *
 * @property int $id
 * @property string $product_name
 * @property float $price
 * @property string $image
 * @property float $stock
 * @property string $description
 * @property Carbon $modified_at
 *
 * @property Product|null $product
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = false;

    protected $casts = [

    ];

    protected $dates = [
        'modified_at'
    ];

    protected $fillable = [
        'product_name',
        'price',
        'image',
        'stock',
        'description',
        'modified_at'
    ];

    public function product_vs_seller()
    {
        return $this->hasMany(ProductVsSeller::class, 'product_id');
    }
}
