<?php

namespace App\Services;

interface IProductGalleryService
{
    public function saveProductGallery($productId, $gallery);

    public function bulkStoreGallery($storeData);
}