<?php

namespace App\Queries\Impls;

use App\Models\ProductTemplate;
use App\Queries\ITemplateQuery;
use App\Models\ProductTemplateSku;
use Illuminate\Support\Facades\Log;
use App\Models\ProductTemplateGallery;
use App\Models\ProductTemplateSkuValue;

class TemplateQueryImpl implements ITemplateQuery
{
    public function getData(int $templateId): ProductTemplate
    {
        \Log::info('template_id', [$templateId]);
        $template = ProductTemplate::where('id', $templateId)->first();
        $template->skues = $this->getSkues($templateId);

        return $template;
    }

    public function getSkues(int $productId)
    {
        $skues = ProductTemplateSku::where('template_id', $productId)->get();
        $galleries = $this->getGalleries($productId);
        foreach ($skues as &$sku) {
            $sku->gallery = !empty($galleries[$sku->id]) ? $galleries[$sku->id] : [];
        }
        $skuValues = ProductTemplateSkuValue::whereIn('sku_id', $skues->pluck('id'))->orderBy('variant_id', 'asc')->get();
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

    public function getGalleries($skuIds)
    {
        $galleries = ProductTemplateGallery::where('product_id', $skuIds)->get();
        $retVal = [];
        foreach ($galleries as $item) {
            if (!isset($retVal[$item->product_id])) {
                $retVal[$item->product_id] = [];
            }
            $retVal[$item->product_id][] = $item->image_url;
        }

        return $retVal;
    }
}
