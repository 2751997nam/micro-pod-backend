<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustom extends Model
{
    protected $table = 'product_custom';

    protected $fillable = [
        'product_id',
        'type'
    ];
}
