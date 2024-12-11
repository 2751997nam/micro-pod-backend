<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "product";
    
    protected $fillable = [
        'sku',
        'barcode',
        'name',
        'slug',
        'image_url',
        'price',
        'high_price',
        'add_shipping_fee',
        'weight',
        'status',
        'description',
        'content',
        'note',
        'inventory',
        'brand_id',
        'status_out_stock',
        'pod_parent_id',
        "approve_advertising",
        'created_at',
        'updated_at',
        'gtin',
        'rating_count',
        'rating_value',
        'is_trademark',
        'is_violation',
        'is_always_on_ads',
        'actor_id',
        'updater_id'
    ];

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class, 'product_id', 'id')->where('type', 'PRODUCT');
    }

    public function videos()
    {
        return $this->hasMany(ProductVideo::class, 'product_id', 'id');
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_n_category')->withPivot(['sorder', 'is_parent'])->withTimestamps();
    }

    public function tags() {
        return $this->belongsToMany('\App\Models\Tag', 'tag_refer' ,'refer_id', 'tag_id')->where('refer_type', TagRefer::REFER_PRODUCT);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
}
