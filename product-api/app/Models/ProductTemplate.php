<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTemplate extends Model
{
    protected $table = 'product_template';

    protected $fillable = [
        'id', 'name', 'product_id_fake'
    ];
}
