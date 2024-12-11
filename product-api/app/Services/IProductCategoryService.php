<?php

namespace App\Services;

interface IProductCategoryService
{
    public function storeProductNCategory(int $productId, array $categoryIds);
}