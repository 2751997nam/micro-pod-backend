<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductNCategory extends Model {
    protected $table = "product_n_category";
    protected $fillable = ['product_id', 'category_id', 'is_parent'];
}
