<?php

namespace App\Services\Impls;

use App\Models\ProductGallery;
use Illuminate\Support\Facades\DB;
use App\Services\IProductGalleryService;
use App\Repositories\IProductGalleryRepository;


class ProductGalleryServiceImpl implements IProductGalleryService
{
    protected IProductGalleryRepository $productGalleryRepo;
    
    public function __construct(IProductGalleryRepository $productGalleryRepo) {
        $this->productGalleryRepo = $productGalleryRepo;
    }

    public function saveProductGallery($productId, $gallery) {
        ProductGallery::where('product_id', $productId)->delete();
        $insertData = [];
        foreach ($gallery as $item) {
            $insertData[] = [
                'product_id' => $productId,
                'image_url' => $item,
                'type' => 'PRODUCT',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ];
        }

        ProductGallery::insert($insertData);
    }

    public function bulkStoreGallery($storeData) {
        $createItems = [];
        $updateItems = [];
        $deletedIds = [];
        foreach ($storeData as $storeDataItem) {
            $productId = $storeDataItem['product_id'];
            $gallery = $storeDataItem['gallery'];
            $type = $storeDataItem['type'];
            $itemExist = ProductGallery::where(['product_id' => $productId, 'type' => $type])->get();
            $itemExistById = [];
            foreach ($itemExist as $item) {
                $itemExistById[$item['id']] = $item;
            }
            $ids = [];
            foreach ($gallery as $item) {
                $newItem = [];
                $newItem['product_id'] = $productId;
                $newItem['image_url'] = $item;
                $newItem['type'] = 'VARIANT';
                $newItem['created_at'] = date('Y-m-d H:i:s', time());
                $newItem['updated_at'] = date('Y-m-d H:i:s', time());
                if (!isset($item['id']) || (isset($item['id']) && !is_numeric($item['id']))) {
                    $createItems[] = $newItem;
                } else if (
                    isset($item['id']) && 
                    isset($itemExistById[$item['id']]) && 
                    $item['image_url'] != $itemExistById[$item['id']]['image_url']
                ) {
                    $updateItems[] = $item;
                    $ids[] = $item['id'];
                }
            }
            if (count($itemExist) > 0) {
                foreach ($itemExist as $item) {
                    if (!in_array($item->id, $ids)) {
                        $deletedIds[] = $item->id;
                    }
                }
            }
        }

        if (!empty($createItems)) {
            foreach (array_chunk($createItems, 100) as $partItems) {
                ProductGallery::insert($partItems);
            }
        }

        if (!empty($updateItems)) {
            foreach (array_chunk($updateItems, 100) as $partItems) {
                $updateTime = date('Y-m-d H:i:s', time());
                $updateSql = 'UPDATE sb_product_gallery `pg` join (';
                foreach ($partItems as $key => $value) {
                    $value['image_url'] = $value['image_url'] ? $value['image_url'] : '';
                    if ($key == 0) {
                        $updateSql .= ('SELECT ' . $value['id'] . " as `id`, '" . $value['image_url'] . "' as `new_image_url`, '" . $updateTime . "' as `new_updated_at`" );
                    } else {
                        $updateSql .= ('UNION ALL SELECT ' . $value['id'] . " , '" . $value['image_url'] . "', '" . $updateTime . "'");
                    }
                }

                $updateSql .= ' ) `values` ON `pg`.id = `values`.id SET `image_url` = `new_image_url`, `updated_at` = `new_updated_at`';
                DB::statement($updateSql);
            }

        }

        if (!empty($deletedIds) > 0) {
            foreach (array_chunk($deletedIds, 100) as $partIds) {
                $this->productGalleryRepo->deleteMany($partIds);
            }
        }
    }
}