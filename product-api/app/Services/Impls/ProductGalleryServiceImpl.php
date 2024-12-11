<?php

namespace App\Services\Impls;

use App\Services\IProductGalleryService;
use App\Repositories\IProductGalleryRepository;


class ProductGalleryServiceImpl implements IProductGalleryService
{
    protected IProductGalleryRepository $productGalleryRepo;
    
    public function __construct(IProductGalleryRepository $productGalleryRepo) {
        $this->productGalleryRepo = $productGalleryRepo;
    }

    public function saveProductGallery($productId, $gallery) {
        
    }
}