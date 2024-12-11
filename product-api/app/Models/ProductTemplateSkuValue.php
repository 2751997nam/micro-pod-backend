<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductTemplateSkuValue extends Model
{
    protected $table = 'product_template_sku_value';

    protected $fillable = [
        'sku_id',
        'variant_id',
        'variant_option_id',
        'product_id',
    ];
}
