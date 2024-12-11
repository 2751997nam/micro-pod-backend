<?php

namespace App\Repositories\Impls;

use App\Models\ProductNCategory;
use App\Repositories\Impls\BaseRepositoryImpl;
use App\Repositories\IProductCategoryRepository;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ProductCategoryRepositoryImpl extends BaseRepositoryImpl implements IProductCategoryRepository
{
    public function getModel() : Builder
    {
        return ProductNCategory::query();
    }

    public function deleteByProductId(int $productId)
    {
        $this->getModel()->where('product_id', $productId)->delete();
    }
}