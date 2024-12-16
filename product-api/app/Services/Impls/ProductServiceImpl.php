<?php

namespace App\Services\Impls;

use Illuminate\Support\Str;
use App\Packages\Utils\Utils;
use App\Services\ILogService;
use App\Services\IProductService;
use App\Services\ITagReferService;
use Illuminate\Support\Facades\DB;
use App\Services\IProductVideoService;
use App\Repositories\IProductRepository;
use App\Services\IProductGalleryService;
use App\Repositories\ITagReferRepository;
use App\Services\IProductCategoryService;
use App\Services\IProductValidatorService;

class ProductServiceImpl implements IProductService
{
    protected IProductRepository $productRepo;
    protected IProductValidatorService $productValidatorService;
    protected IProductCategoryService $productCategoryService;
    protected ITagReferRepository $tagReferRepo;
    protected IProductGalleryService $productGalleryService;
    protected IProductVideoService $productVideoService;
    protected ITagReferService $tagReferService;
    protected ILogService $logService;
    
    public function __construct(
        IProductRepository $productRepo,
        IProductValidatorService $productValidatorService,
        IProductCategoryService $productCategoryService,
        IProductGalleryService $productGalleryService,
        IProductVideoService $productVideoService,
        ITagReferService $tagReferService,
        ILogService $logService
    ) {
        $this->productRepo = $productRepo;
        $this->productValidatorService = $productValidatorService;
        $this->productCategoryService = $productCategoryService;
        $this->productGalleryService = $productGalleryService;
        $this->productVideoService = $productVideoService;
        $this->tagReferService = $tagReferService;
        $this->logService = $logService;
    }

    public function saveProduct(array $data) {
        $input = $data['data'];
        $user = $data['user'];
        $response = [
            'status' => 'fail',
            'message' => '',
            'result' => null
        ];
        try {
            DB::beginTransaction();
            set_time_limit(30 * 60);
            $this->generateSku($input);
            $valiateErrMsg = $this->productValidatorService->validate($input);

            $response['message'] = $valiateErrMsg;
            if ($valiateErrMsg) {
                return $response;
            }
            $data = $this->buildProductData($input);
            $logData = [
                'target_type' => 'PRODUCT', 
                'actor_email' => !empty($user) ? $user['email'] : null
            ];
            $dataLog = [
                'product' => []
            ];
            if (!empty($input['id'])) {
                $data["updater_id"] = $user['id'];
                $dataLog['product'] = $data;
                $product = $this->productRepo->update($data);
                $logData['event_type'] = 'UPDATE';
                $logData['target_id'] = $input['id'];
            } else {
                $data["actor_id"] = $user['id'];
                $dataLog['product'] = $data;
                $product = $this->productRepo->create($data);
                $logData['event_type'] = 'CREATE';
                $logData['target_id'] = $product->id;
            }
            $logData['created_at'] = new \DateTime();
            \Log::info('product', [$product]);
            if ($product && isset($product->id)) {
                $categoryLog = [];
                $tagLog = [];
                $galleryLog = [];
                $this->productCategoryService->storeProductNCategory($product->id, !empty($input['categoryIds']) ? $input['categoryIds'] : []);
                
                $this->tagReferService->storeTagRefer($product->id, 'PRODUCT', !empty($input['tagIds']) ? $input['tagIds'] : []);
    
                $this->productGalleryService->saveProductGallery($product->id, !empty($input['gallery']) ? $input['gallery'] : []);
    
                // $this->productVideoService->saveProductVideo($product->id, !empty($input['videos']) ? $input['videos'] : []);
    
                $dataLogCollection = [];
                if (!empty($galleryLog)) {
                    $dataLogCollection['gallery'] = $galleryLog;
                }
                if (!empty($categoryLog)) {
                    $dataLogCollection['category'] = $categoryLog;
                }
                if (!empty($tagLog)) {
                    $dataLogCollection['tags'] = $tagLog;
                }
                if (!empty($dataLogCollection)) {
                    $dataLog['collections'] = $dataLogCollection;
                }
                $logData['data'] = json_encode($dataLog);
                $this->logService->writeProductLog($dataLog, $product->id, $logData['event_type'], $user['email']);
                $response['status'] = 'successful';
                $response['result'] = $product->id;
            }
            
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            \Log::error($ex);
            $response['message'] = $ex->getMessage() . ' ' . $ex->getTraceAsString();
        }
        return $response;
    }

    protected function generateSku($input) {
        if (empty($input['sku'])) {
            $input['sku'] = Str::uuid();
        }
    }

    protected function buildProductData($input) {
        $retVal = [
            'price' => 0,
            'high_price' => 0,
            'add_shipping_fee' => 0,
            'weight' => 0,
            'description' => '',
            'content' => '',
            'brand_id' => null,
            'image_url' => '',
            'gtin' => null
        ];
        if (array_key_exists('id', $input) && $input['id'] != '') {
            $retVal['id'] = $input['id'];
        }
        if (array_key_exists('slug', $input) && $input['slug'] != '') {
            $retVal['slug'] = trim($input['slug'], '-');
        }
        if (array_key_exists('name', $input) && $input['name'] != '') {
            $retVal['name'] = $input['name'];
            if (!array_key_exists('slug', $input) || (array_key_exists('slug', $input) && $input['slug'] == '')) {
                $retVal['slug'] = Utils::sluggify($input['name']);
            }
        }
        if (isset($retVal['slug']) && strlen($retVal['slug']) > 200) {
            $retVal['slug'] = substr($retVal['slug'], 0, 200);
        }
        if (array_key_exists('status', $input) && $input['status'] != '') {
            $retVal['status'] = $input['status'];
        }
        if (array_key_exists('status_out_stock', $input) && $input['status_out_stock'] != '') {
            $retVal['status_out_stock'] = $input['status_out_stock'];
        }
        if (array_key_exists('price', $input) && $input['price']) {
            $retVal['price'] = $input['price'];
        }
        if (array_key_exists('high_price', $input) && $input['high_price']) {
            $retVal['high_price'] = $input['high_price'];
        }
        if (array_key_exists('add_shipping_fee', $input) && $input['add_shipping_fee']) {
            $retVal['add_shipping_fee'] = $input['add_shipping_fee'];
        }
        if (array_key_exists('weight', $input) && $input['weight']) {
            $retVal['weight'] = $input['weight'];
        }
        if (array_key_exists('sku', $input) && $input['sku'] != '') {
            $retVal['sku'] = $input['sku'];
        }
        if (array_key_exists('barcode', $input) && $input['barcode'] != '') {
            $retVal['barcode'] = $input['barcode'];
        } else {
            $retVal['barcode'] = '';
        }
        if (array_key_exists('description', $input) && $input['description']) {
            $retVal['description'] = $input['description'];
        }
        if (array_key_exists('content', $input) && $input['content']) {
            $retVal['content'] = $input['content'];
        }
        if (array_key_exists('brand_id', $input) && $input['brand_id'] != '') {
            $retVal['brand_id'] = $input['brand_id'];
        }
        if (array_key_exists('inventory', $input) && $input['inventory'] !== '') {
            $retVal['inventory'] = $input['inventory'];
        }
        if (array_key_exists('image_url', $input) && $input['image_url'] != '') {
            $retVal['image_url'] = $input['image_url'];
        }
        if (array_key_exists('gtin', $input) && $input['gtin'] != '') {
            $retVal['gtin'] = $input['gtin'];
        }
        if (array_key_exists('note', $input) && $input['note'] != '') {
            $retVal['note'] = $input['note'];
        }

        if (array_key_exists('is_trademark', $input)) {
            $retVal['is_trademark'] = $input['is_trademark'];
        }

        if (array_key_exists('is_violation', $input)) {
            $retVal['is_violation'] = $input['is_violation'];
        }

        if (array_key_exists('approve_advertising', $input)) {
            $retVal['approve_advertising'] = $input['approve_advertising'];
        }
        return $retVal;
    }
}