<?php

namespace App\Models;

use App\Models\ProductVariantOption;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variant';

    protected $fillable = [
        'slug', 'name', 'type', 'display_name'
    ];

    public function variantOptions()
    {
        return $this->hasMany(ProductVariantOption::class, 'variant_id', 'id');
    }
}
