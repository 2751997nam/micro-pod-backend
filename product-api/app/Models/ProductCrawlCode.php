<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCrawlCode extends Model
{
    protected $table = 'product_crawl_code';
    protected $fillable = ['product_id', 'value', 'site'];
}