<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductSkuValue extends Model
{
    protected $table = 'product_sku_value';
    protected $fillable = [
        'product_id',
        'sku_id',
        'variant_id',
        'variant_option_id',
    ];
}
