<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVideo extends Model
{
    protected $table = 'product_video';

    protected $fillable = [
        'product_id',
        'image_url',
        'src',
        'status',
    ];
}
