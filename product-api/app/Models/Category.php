<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $table = "category";

    const TYPE_PRODUCT = 'PRODUCT';
    const TYPE_POST = 'POST';

    protected $fillable = [
        'name', 'type', 'is_hidden', 'image_url', 'big_image_url', 
        'description', 'short_description', 'slug', 'sorder', 'parent_id', 'is_display_home_page', 
        'tags', 'is_show_product_content', 'is_valid_print_back', 'sell_design', 'is_three_dimenstion'
    ];

    public function parents() {
        return $this->belongsTo('\App\Models\Category', 'parent_id');
    }
}
