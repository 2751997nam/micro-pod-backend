<?php

namespace App\Services;

interface IProductVideoService
{
    public function saveProductVideo($productId, $videos);
}