<?php

namespace App\Repositories\Impls;

use App\Models\ProductGallery;
use App\Repositories\IProductGalleryRepository;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ProductGalleryRepositoryImpl extends BaseRepositoryImpl implements IProductGalleryRepository
{
    public function getModel() : Builder
    {
        return ProductGallery::query();
    }

    public function deleteByTypeAndProductId(string $type, int $productId)
    {
        $this->getModel()->where('type', $type)->where('product_id', $productId)->delete();
    }
}