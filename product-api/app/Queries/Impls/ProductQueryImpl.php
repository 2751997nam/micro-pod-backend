<?php

namespace App\Queries\Impls;

use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariant;
use App\Queries\IProductQuery;
use App\Models\ProductSkuValue;
use App\Models\ProductVariantOption;
use App\Repositories\IProductRepository;

class ProductQueryImpl implements IProductQuery
{
    private $productRepo;

    public function __construct(IProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function getData(int $productId): Product
    {
        $product = $this->productRepo
            ->getModel()
            ->with(['categories', 'galleries', 'tags', 'brand'])
            ->where('id', $productId)
            ->first();
        $skuValues = ProductSkuValue::where('product_id', $productId)->orderBy('variant_id', 'asc')->get();
        $product->productVariants = $this->getSkues($productId, $skuValues);
        $product->variants = $this->getVariants($skuValues);

        return $product;
    }

    public function getSkues(int $productId, $skuValues)
    {
        $skues = ProductSku::where('product_id', $productId)->get();
        $optionIdBySkuId = [];
        foreach ($skuValues as $skuValue) {
            if (!array_key_exists($skuValue->sku_id, $optionIdBySkuId)) {
                $optionIdBySkuId[$skuValue->sku_id] = [];
            }
            $optionIdBySkuId[$skuValue->sku_id][$skuValue->variant_id] = $skuValue->variant_option_id;
        }
        foreach ($skues as &$sku) {
            $sku->options = array_values($optionIdBySkuId[$sku->id]);
        }

        return $skues;
    }

    public function getVariants($skuValues)
    {
        $optionIdByVariantId = [];
        foreach ($skuValues as $skuValue) {
            if (!array_key_exists($skuValue->variant_id, $optionIdByVariantId)) {
                $optionIdByVariantId[$skuValue->variant_id] = [];
            }
            $optionIdByVariantId[$skuValue->variant_id][] = $skuValue->variant_option_id;
        }
        $variants = ProductVariant::whereIn('id', array_keys($optionIdByVariantId))->get();
        foreach ($variants as &$variant) {
            $optionIds = $optionIdByVariantId[$variant->id];
            $options = ProductVariantOption::whereIn('id', $optionIds)->get();
            $variant->options = $options;
        }

        return $variants;
    }
}