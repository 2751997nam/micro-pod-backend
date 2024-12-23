<?php

namespace App\Services\Impls;

use App\Packages\Utils\Utils;
use App\Models\ProductVariant;
use App\Services\IVariantService;
use App\Models\ProductVariantOption;

class VariantServiceImpl implements IVariantService
{
    public function storeVariants($input) {
        $retVal = [];
        if (isset($input['variants']) && count($input['variants']) > 0) {
            foreach($input['variants'] as $variant) {
                if (isset($variant['name']) && $variant['name'] != ''
                    && isset($variant['values']) && count($variant['values']) > 0) {
                    if (!empty($variant['slug'])) {
                        $variantSlug = $variant['slug'];
                    } else {
                        $variantSlug = Utils::sluggify($variant['name']);
                    }
                    $saveVariant = [
                        'name' => $variant['name'],
                        'slug' => $variantSlug,
                        'type' => $variant['type'],
                    ];
                    if (!array_key_exists('id', $variant)
                        || (array_key_exists('id', $variant) && !is_numeric($variant['id']))
                    ) {
                        $variantObj = ProductVariant::create($saveVariant);
                        $saveVariant['id'] = $variantObj->id;
                    } else {
                        $saveVariant['id'] = $variant['id'];
                        ProductVariant::where('id', $variant['id'])->update($saveVariant);
                    }

                    foreach ($variant['values'] as $value) {
                        $target = [
                            'variantId' => $saveVariant['id']
                        ];
                        if (!empty($value['slug'])) {
                            $slug = $value['slug'];
                        } else {
                            $slug = Utils::sluggify($value['name']);
                        }
                        if (!array_key_exists('id', $value) || (array_key_exists('id', $value) && !is_numeric($value['id']))) {
                            $create = [
                                'variant_id' => $saveVariant['id'],
                                'name' => $value['name'],
                                'slug' => $slug,
                                'image_url' => isset($value['image_url']) ? $value['image_url'] : null,
                            ];
                            $option = ProductVariantOption::create($create);
                            if ($option) {
                                $target['variantOptionId'] = $option->id;
                            }
                        } else {
                            if (isset($value['image_url'])) {
                                ProductVariantOption::where('id', $value['id'])->update([
                                    'id' => $value['id'],
                                    'image_url' => $value['image_url'],
                                ]);
                            }
                            $target['variantOptionId'] = $value['id'];
                        }
                        $retVal[$variantSlug . '-+-' . $slug] = $target;
                    }
                }
            }
        }
        return $retVal;
    }
}