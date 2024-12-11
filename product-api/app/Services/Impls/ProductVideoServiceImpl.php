<?php

namespace App\Services\Impls;

use App\Services\IProductVideoService;
use App\Repositories\IProductVideoRepository;


class ProductVideoServiceImpl implements IProductVideoService
{
    protected IProductVideoRepository $productVideoRepo;
    
    public function __construct(IProductVideoRepository $productVideoRepo) {
        $this->productVideoRepo = $productVideoRepo;
    }

    public function saveProductVideo($productId, $videos) {
        if (!empty($videos)) {
            $saveIds = [];
            foreach ($videos as $item) {
                if (!empty($item['id'])) {
                    $video = $this->productVideoRepo->find($item['id']);
                    if ($video) {
                        $video->src = $item['src'];
                        $video->image_url = $item['image_url'];
                        $video->save();
                        $saveIds[] = $video->id;
                    }
                } else {
                    $item['product_id'] = $productId;
                    $video = $this->productVideoRepo->create($item);
                    $saveIds[] = $video->id;
                }
            }
             $this->productVideoRepo->getModel()->where('product_id', $productId)->whereNotIn('id', $saveIds)->delete();
        } else {
             $this->productVideoRepo->getModel()->where('product_id', $productId)->delete();
        }
    }
}