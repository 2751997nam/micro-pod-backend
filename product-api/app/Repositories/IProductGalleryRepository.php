<?php

namespace App\Repositories;

use App\Repositories\IBaseRepository;

interface IProductGalleryRepository extends IBaseRepository {

    public function deleteByTypeAndProductId(string $type, int $productId);
}