<?php

namespace App\Services;

interface IProductSkuService
{
    public function saveProductSkues($input, $tmpVariants, $productId);
}