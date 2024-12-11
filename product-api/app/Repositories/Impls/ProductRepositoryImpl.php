<?php

namespace App\Repositories\Impls;

use App\Models\Product;
use App\Repositories\IProductRepository;
use App\Repositories\Impls\BaseRepositoryImpl;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ProductRepositoryImpl extends BaseRepositoryImpl implements IProductRepository
{
    public function getModel() : Builder
    {
        return Product::query();
    }
}