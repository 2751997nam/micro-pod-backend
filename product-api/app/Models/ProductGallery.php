<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    protected $table = 'product_gallery';

    protected $fillable = [
        'product_id',
        'image_url',
        'description',
        'type',
        'updated_at',
        'created_at',
    ];
}
