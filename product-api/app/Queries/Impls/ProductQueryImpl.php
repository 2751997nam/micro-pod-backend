<?php

namespace App\Queries\Impls;

use App\Models\Product;
use App\Queries\IProductQuery;
use App\Repositories\IProductRepository;

class ProductQueryImpl implements IProductQuery
{
    private $productRepo;

    public function __construct(IProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function getData(int $productId): Product
    {
        $product = $this->productRepo
            ->getModel()
            ->with(['categories', 'galleries', 'tags', 'brand'])
            ->where('id', $productId)
            ->first();

        return $product;
    }
}