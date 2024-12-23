<?php

namespace App\Services\Impls;

use App\Models\LogModel;
use App\Models\ProductSku;
use Illuminate\Support\Str;
use App\Packages\Utils\Utils;
use App\Services\ILogService;
use App\Models\ProductGallery;
use App\Models\ProductSkuValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\IProductSkuService;
use App\Services\IProductGalleryService;

class ProductSkuServiceImpl implements IProductSkuService
{
    protected IProductGalleryService $productGalleryService;

    public function __construct(
        IProductGalleryService $productGalleryService
    ) {
        $this->productGalleryService = $productGalleryService;
    }

    public function saveProductSkues($input, $tmpVariants, $productId) {
        $createIds = [];
        $updateIds = [];
        $deletedIds = [];
        $deletedValueIds = [];
        $itemExists = ProductSku::where('product_id', $productId)->with('skuValues')->get();
        if (isset($input['productVariants']) && count($input['productVariants']) > 0) {
            $existIds = [];
            $valueBySkus = [];
            $countVariantExits = 0;
            if (count($itemExists) > 0) {
                $countVariantExits = count($itemExists[0]->skuValues);
                foreach ($itemExists as $item) {
                    $existIds[] = $item->id;
                    foreach ($item->skuValues as $it) {
                        if (!array_key_exists($item->id, $valueBySkus)) {
                            $valueBySkus[$item->id] = [];
                        }
                        $valueBySkus[$item->id][] = $it->id;
                    }
                }
            }
            $ids = [];
            $firstProductVariant = ['variants' => []];
            foreach ($input['productVariants'] as $item) {
                $firstProductVariant = $item;
                break;
            }
            $countVariant = count($firstProductVariant['variants']);
            $clear = 0;
            if ($countVariantExits != $countVariant) {
                $clear = 1;
            }
            if ($clear) {
                $this->removeAllSku($productId);
            }

            $oldProductSkus = $itemExists->keyBy('id')->toArray();

            $oldProductSkusByKey = $this->buildSkuByKey($oldProductSkus);

            $storeGalleryData = [];
            $updateSkuCodeData = [];
            $deleteGallerySkuIds = [];
            $storeProductSkuValueData = [];

            foreach ($input['productVariants'] as $key => $item) {
                if ($clear && isset($item['id'])) {
                    unset($item['id']);
                }
                $isCreate = !isset($item['id']) || (isset($item['id']) && !is_numeric($item['id']));
                $productSku = $this->buildProductSkuItem($item, $productId);
                if ($isCreate) {
                    $productSku['sku'] = 'P' . $productId . '-' . Str::random(10);
                }
                $this->buildSkuOptionSlug($item);
                if (!$clear && $isCreate) {
                    $oldSku = $this->getOldSku($item, $tmpVariants, $oldProductSkusByKey);
                    if ($oldSku) {
                        $item['id'] = $oldSku['id'];
                    }
                }

                if ($isCreate) {
                    $sku = ProductSku::create($productSku);
                    $productSku['id'] = $sku->id;
                    $createIds[] = $sku->id;
                    if ($sku) {
                        foreach($item['variants'] as $variant) {
                            if (!isset($variant['variant_slug']) || !isset($variant['slug'])) {
                                continue;
                            }
                            $key = $variant['variant_slug'] . '-+-' . $variant['slug'];
                            if (isset($tmpVariants[$key])) {
                                $storeProductSkuValueData[] = [
                                    'sku_id' => $sku->id,
                                    'product_id' => $productId,
                                    'variant_id' => $tmpVariants[$key]['variantId'],
                                    'variant_option_id' => $tmpVariants[$key]['variantOptionId'],
                                ];
                            }
                        }
                    }

                    if (!isset($item['sku']) || !$item['sku']) {
                        $updateSkuCodeData[] = ['id' => $sku->id, 'sku' => 'P' . $productId . '-' . Str::random(10)];
                    }
                } else if (isset($oldProductSkus[$item['id']])) {
                    
                    $productSku['id'] = $item['id'];
                    if (!isset($item['sku']) || !$item['sku']) {
                        $productSku['sku'] = 'P' . $productId . '-' . Str::random(10);
                    }
                    $changeSkuData = $this->getProductSkuChangeData($productSku, $oldProductSkus[$item['id']]);
                    
                    if (count($changeSkuData)) {
                        $changeSkuData['id'] = $item['id'];
                        $updateIds[] = $item['id'];
                        ProductSku::where('id', $item['id'])->update($changeSkuData); 
                    }
                    $ids[] = $item['id'];
                }
                if (!empty($item['gallery'])) {
                    $gallery = $item['gallery'];
                } else {
                    $gallery = [];
                }
                if (count($gallery) > 0) {
                    $storeGalleryData[] = [
                        'product_id' => $productSku['id'],
                        'gallery' => $gallery,
                        'type' => 'VARIANT',
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ];
                } else if (!empty($productSku['id'])) {
                    $deleteGallerySkuIds[] = $productSku['id'];
                }
            }

            foreach (array_chunk($updateSkuCodeData, 100) as $partUpdateSkuData) {
                $updateSql = 'UPDATE sb_product_sku `ps` join (';
                foreach ($partUpdateSkuData as $key => $value) {
                    $value['sku'] = $value['sku'] ? $value['sku'] : '';
                    if ($key == 0) {
                        $updateSql .= ('SELECT ' . $value['id'] . " as `id`, '" . $value['sku'] . "' as `new_sku` ");
                    } else {
                        $updateSql .= ('UNION ALL SELECT ' . $value['id'] . " , '" . $value['sku'] . "' ");
                    }
                }

                $updateSql .= ' ) `values` ON `ps`.id = `values`.id SET `sku` = `new_sku`';
                DB::statement($updateSql);
            }

            if (!empty($storeProductSkuValueData)) {
                foreach (array_chunk($storeProductSkuValueData, 100) as $partData) {
                    ProductSkuValue::insert($partData);
                }
            }

            if (!empty($storeGalleryData)) {
                $this->productGalleryService->bulkStoreGallery($storeGalleryData);
            }

            if (!empty($deleteGallerySkuIds) > 0) {
                foreach (array_chunk($deleteGallerySkuIds, 100) as $partDeleteIds) {
                    ProductGallery::whereIn('product_id', $partDeleteIds)->where('type', 'VARIANT')->delete();
                }
            }

            if (!$clear && count($existIds) > 0 && count($ids) > 0) {
                foreach ($existIds as $item) {
                    if (!in_array($item, $ids) && isset($valueBySkus[$item])) {
                        $deletedIds[] = $item;
                        $deletedValueIds = array_merge($deletedValueIds, $valueBySkus[$item]);
                    }
                }
            } elseif (!$clear && count($existIds) && count($ids) == 0) {
                foreach ($existIds as $item) {
                    $deletedIds[] = $item;
                    if (isset($valueBySkus[$item])) {
                        $deletedValueIds = array_merge($deletedValueIds, $valueBySkus[$item]);
                    }
                }
            }
        } else if (count($itemExists) > 0) {
            foreach ($itemExists as $item) {
                $deletedIds[] = $item->id;
                foreach ($item->skuValues as $it) {
                    $deletedValueIds[] = $it->id;
                }
            }
        }
        $variantLog = [];
        if (!empty($createIds)) {
            $variantLog['create'] = ProductSku::whereIn('id', $createIds)->get();
        }
        if (!empty($updateIds)) {
            $variantLog['update'] = ProductSku::whereIn('id', $updateIds)->get();
        }
        if (count($deletedIds) > 0) {
            $variantLog['delete'] = ProductSku::whereIn('id', $deletedIds)->get();
            DB::table('product_sku_value')->whereIn('sku_id', $deletedIds)->delete();
            ProductSku::whereIn('id', $deletedIds)->delete();
        }
        if (!empty($variantLog)) {
            $user = isset($GLOBALS['globalUser']) ? $GLOBALS['globalUser'] : null;
            $log = [
                'data' => json_encode($variantLog),
                'target_type' => 'PRODUCT',
                'target_id' => $productId,
                'event_type' => 'CHANGE_PRODUCT_VARIANT',
                'actor_email' => !empty($user) ? $user->email : null,
                'created_at' => new \DateTime()
            ];
            LogModel::insert($log);
        }
        return [
            'createIds' => $createIds,
            'updateIds' => $updateIds,
            'deletedIds' => $deletedIds,
            'deletedValueIds' => $deletedValueIds,
        ];
    }

    protected function getProductSkuChangeData($data, $oldData) {
        $retVal = [];

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $oldData) && $data[$key] != $oldData[$key]) {
                $retVal[$key] = $value;
            }
        }

        return $retVal;
    }

    protected function buildSkuOptionSlug(&$sku) {
        foreach($sku['variants'] as &$variant) {
            if (!empty($variant['variant_slug'])) {
                $slugVariant = $variant['variant_slug'];
            } else {
                $slugVariant = Utils::sluggify($variant['variant']);
                $variant['variant_slug'] = $slugVariant;
            }
            if (!empty($variant['slug'])) {
                $slugValueVariant = $variant['slug'];
            } else {
                $slugValueVariant = Utils::sluggify($variant['name']);
            }
            $variant['slug'] = $slugValueVariant;
        }
    }

    protected function buildProductSkuItem($item, $productId) {
        $productSku = [
            'product_id' => $productId,
            'price' => 0,
            'high_price' => 0,
            'image_url' => ''
        ];
        if (isset($item['sku']) && $item['sku']) {
            $productSku['sku'] = $item['sku'];
        }
        if (array_key_exists('barcode', $item)) {
            $productSku['barcode'] = $item['barcode'];
        }
        if (array_key_exists('inventory', $item)) {
            $productSku['inventory'] = $item['inventory'];
        }
        if (isset($item['price']) && $item['price'] > 0) {
            $productSku['price'] = $item['price'];
        }
        if (isset($item['high_price']) && $item['high_price'] > 0) {
            $productSku['high_price'] = $item['high_price'];
        }
        if (array_key_exists('image_url', $item)) {
            $productSku['image_url'] = $item['image_url'];
        }
        if (array_key_exists('is_default', $item)) {
            $productSku['is_default'] = $item['is_default'];
        }
        if (array_key_exists('status', $item)) {
            $productSku['status'] = $item['status'];
        } else {
            $productSku['status'] = 'ACTIVE';
        }

        return $productSku;
    }

    protected function getOldSku($sku, $tmpVariants, $oldProductSkusByKey) {
        $skuKey = [];
        foreach($sku['variants'] as &$variant) {
            $key = $variant['variant_slug'] . '-+-' . $variant['slug'];
            if (isset($tmpVariants[$key])) {
                $skuKey[$tmpVariants[$key]['variantId']] = $tmpVariants[$key]['variantOptionId'];
            }
        }
        ksort($skuKey);

        if (isset($oldProductSkusByKey[implode("-", $skuKey)])) {
            return $oldProductSkusByKey[implode("-", $skuKey)];
        }

        return null;
    }

    protected function removeAllSku($productId) {
        $itemDeleteds = ProductSku::where('product_id', $productId)->get();
 
        ProductSkuValue::where('product_id', $productId)->delete();
        if (!empty($itemDeleteds)) {
            $user = isset($GLOBALS['globalUser']) ? $GLOBALS['globalUser'] : null;
            $log = [
                'data' => json_encode($itemDeleteds),
                'target_type' => 'PRODUCT',
                'target_id' => $productId,
                'event_type' => 'CHANGE_VARIANT_AND_CLEAR_PRODUCT_VARIANT',
                'actor_email' => !empty($user) ? $user->email : null,
                'created_at' => new \DateTime()
            ];
            LogModel::insert($log);
        }
    }

    protected function buildSkuByKey($skues) {
        $retVal = [];

        foreach ($skues as $item) {
            $keys = [];
            foreach ($item['sku_values'] as $value) {
                $keys[$value['variant_id']] = $value['variant_option_id'];
            }
            ksort($keys);
            $retVal[implode("-", $keys)] = $item;
        }

        return $retVal;
    }

}