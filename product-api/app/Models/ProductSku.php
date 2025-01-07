<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductSkuValue;
use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    protected $table = 'product_sku';
    protected $fillable = [
        'sku',
        'barcode',
        'price',
        'high_price',
        'image_url',
        'product_id',
        'is_default',
        'sale_percent',
        'display_drop_price',
        'inventory',
        'status'
    ];

    public function skuValues()
    {
        return $this->hasMany(ProductSkuValue::class, 'sku_id', 'id');
    }
}
