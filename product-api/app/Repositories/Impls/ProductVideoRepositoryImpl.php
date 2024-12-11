<?php

namespace App\Repositories\Impls;

use App\Models\ProductVideo;
use App\Repositories\IProductVideoRepository;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ProductVideoRepositoryImpl extends BaseRepositoryImpl implements IProductVideoRepository
{
    public function getModel() : Builder
    {
        return ProductVideo::query();
    }
}