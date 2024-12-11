<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model
{
    protected $table = 'product_meta';

    protected $fillable = [
        'product_id',
        'key',
        'value',
    ];
}
