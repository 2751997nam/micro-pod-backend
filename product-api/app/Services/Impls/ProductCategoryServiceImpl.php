<?php

namespace App\Services\Impls;

use App\Services\IProductCategoryService;
use App\Repositories\IProductCategoryRepository;

class ProductCategoryServiceImpl implements IProductCategoryService
{
    protected IProductCategoryRepository $productCategoryRepo;
    
    public function __construct(IProductCategoryRepository $productCategoryRepo) {
        $this->productCategoryRepo = $productCategoryRepo;
    }

    public function storeProductNCategory(int $productId, array $categoryIds) {
        $itemExists = $this->productCategoryRepo->getModel()->where('product_id', $productId)->get();
        if (count($itemExists) > 0) {
            $idDeleteds = [];
            foreach($itemExists as $item) {
                if (!in_array($item->category_id, $categoryIds)) {
                    $idDeleteds[] = $item->id;
                } else {
                    if (($key = array_search($item->category_id, $categoryIds)) !== false) {
                        unset($categoryIds[$key]);
                    }
                }
            }
            if (count($idDeleteds) > 0) {
                $this->productCategoryRepo->deleteMany($idDeleteds);
            }
        }
        if (count($categoryIds) > 0) {
            foreach($categoryIds as $categoryId) {
                $newItem = [
                    'product_id' => $productId,
                    'category_id' => $categoryId,
                    'created_at' => new \DateTime(),
                    'updated_at' => new \DateTime(),
                ];
                $this->productCategoryRepo->create($newItem);
            }
        }
    }
}
