<?php

namespace App\Models;
use Utils;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
    protected $table = "tag";

    protected $fillable = [
        'title', 'slug', 'description'
    ];

    public function tagRefers()
    {
        return $this->hasMany(TagRefer::class)->limit(10);
    }
}
