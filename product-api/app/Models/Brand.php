<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brand';

    protected $fillable = [
        'name',
        'slug',
        'image_url',
        'description',
        'sorder',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
