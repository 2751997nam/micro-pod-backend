<?php

namespace App\Models;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;

class ProductVariantOption extends Model
{
    protected $table = 'product_variant_option';

    protected $fillable = [
        'variant_id', 'name', 'slug', 'code', 'image_url'
    ];

    public function variant() {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
