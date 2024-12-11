<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomOption extends Model
{
    protected $table = 'product_custom_option';

    protected $fillable = [
        'product_id',
        'type',
        'value'
    ];
}
