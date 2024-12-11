<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TagRefer extends Model {
    protected $table = "tag_refer";

    const REFER_PRODUCT = 'PRODUCT';
    const REFER_POST = 'POST';
    public $timestamps = false;
}
