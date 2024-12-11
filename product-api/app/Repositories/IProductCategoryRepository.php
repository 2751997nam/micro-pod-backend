<?php

namespace App\Repositories;

use App\Repositories\IBaseRepository;

interface IProductCategoryRepository extends IBaseRepository {
    public function deleteByProductId(int $productId);
}