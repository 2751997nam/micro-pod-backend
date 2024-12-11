<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

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
}
