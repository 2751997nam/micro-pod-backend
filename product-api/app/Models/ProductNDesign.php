<?php

namespace App\Models;

use  Illuminate\Database\Eloquent\Model;

class ProductNDesign extends Model
{
    protected $table = 'product_n_design';

    protected $fillable = [
        'id', 'product_id', 'design_id', 'is_primary'
    ];
}
