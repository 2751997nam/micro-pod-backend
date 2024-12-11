<?php

namespace App\Queries;

use App\Models\Product;

interface IProductQuery
{
    public function getData(int $productId): Product;
}