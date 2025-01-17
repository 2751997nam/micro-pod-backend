<?php

namespace App\Services\Impls;

use App\Helpers\ImageSlugUtil;
use App\Helpers\DesignCodeUtil;
use App\Models\ProductTemplate;
use Illuminate\Support\Facades\DB;
use App\Services\IProductTemplateService;

class ProductTemplateServiceImpl implements IProductTemplateService
{
    public function saveTemplate(array $input) {
        $productId = $input['product_id'];
        $name = $input['name'];
        $categoryId = !empty($input['category_id']) ? $input['category_id'] : null;
        $category = null;
        if (!$categoryId) {
            $category = $this->getCategory($productId);
            $categoryId = $category->id;
            $categorySlug = $category->slug;
        } else {
            $category = DB::table('category')->where('id', $categoryId)->first(['id', 'name', 'slug']);
            $categorySlug = $category->slug;
        }

        $template = $this->storeTemplate($input);
        
        $numOfDesign = !empty($input['num_of_design']) ? $input['num_of_design'] : 1;
        $designType = !empty($input['design_type']) ? $input['design_type'] : 'default'; //default, ecrypted
        $imageCount = !empty($input['image_count']) ? $input['image_count'] : 1;
        if ($name) {
            DB::table('product_template')->where([
                'id' => $template->id,
            ])->update([
                'name' => $name,
                'product_id_fake' => $productId,
                'image_per_sku' => $imageCount,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
        }

        DB::beginTransaction();
        try {
            $skus = DB::table('product_sku')->where('product_id', $productId)->get();
            $templateSkuIds = DB::table('product_template_sku')->where('template_id', $template->id)->get(['id'])->pluck('id');
            DB::table('product_template_sku')->where('template_id', $template->id)->delete();
            DB::table('product_template_sku_value')->whereIn('sku_id', $templateSkuIds)->delete();
            DB::table('product_template_gallery')->whereIn('product_id', $templateSkuIds)->where('type', 'VARIANT')->delete();
            
            $insertSkuData = [];
            $insertSkuValueData = [];
            $insertGalleryData = [];
            $skuIds = $skus->pluck('id')->toArray();
            $skuValuesBySkuId = [];
            $optionIds = [];
            $variantIds = [];
            foreach (array_chunk($skuIds, 500) as $chunk) {
                $values = DB::table('product_sku_value')->whereIn('sku_id', $chunk)->get();
                foreach ($values as $value) {
                    if (!isset($skuValuesBySkuId[$value->sku_id])) {
                        $skuValuesBySkuId[$value->sku_id] = [];
                    }
                    $optionIds[] = $value->variant_option_id;
                    $variantIds[] = $value->variant_id;
                    $skuValuesBySkuId[$value->sku_id][$value->variant_id] = $value;
                }
            }
            $optionsById = DB::table('product_variant_option')
                ->whereIn('id', array_unique($optionIds))
                ->get()
                ->keyBy('id')
                ->toArray();
            $variantsById = DB::table('product_variant')
                ->whereIn('id', array_unique($variantIds))
                ->get()
                ->keyBy('id');
            foreach ($skus as $sku) {
                if (empty($skuValuesBySkuId[$sku->id])) {
                    continue;
                }
                $skuValues = $skuValuesBySkuId[$sku->id];
                $options = [];
                foreach ($skuValues as $skuValue) {
                    if (isset($optionsById[$skuValue->variant_option_id])) {
                        $options[] = (array) $optionsById[$skuValue->variant_option_id];
                    }
                }
                foreach ($options as &$option) {
                    if (!isset($variantsById[$option['variant_id']])) {
                        $option['variant_slug'] = '';
                        continue;
                    }
                    $variant = $variantsById[$option['variant_id']];
                    if ($variant) {
                        $option['variant_slug'] = $variant->slug;
                    }
                }
                $imageIndex = false;
                if ($imageCount && $imageCount > 1) {
                    $imageIndex = 1;
                    for ($i = 2; $i <= $imageCount; $i++) {
                        $imageUrl = $this->buildProductImage($categorySlug, $options, $designType, $numOfDesign, $i);
                        if (!$imageUrl) {
                            continue;
                        }
                        $insertGalleryData[] = [
                            'product_id' => $sku->id,
                            'type' => 'VARIANT',
                            'image_url' => $imageUrl,
                            'created_at' => date('Y-m-d H:i:s', time()),
                            'updated_at' => date('Y-m-d H:i:s', time())
                        ];
                    }
                }

                $insertSkuData[] = [
                    'id' => $sku->id,
                    'template_id' => $template->id,
                    'sku' => str_replace('P'. $productId, '', $sku->sku),
                    'image_url' => $this->buildProductImage($categorySlug, $options, $designType, $numOfDesign, $imageIndex),
                    'price' => $sku->price,
                    'high_price' => $sku->high_price,
                    'is_default' => $sku->is_default,
                    'status' => $sku->status,
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time())
                ];

                foreach ($skuValues as $value) {
                    $insertSkuValueData[] = [
                        'sku_id' => $value->sku_id,
                        'variant_id' => $value->variant_id,
                        'variant_option_id' => $value->variant_option_id
                    ];
                }
            }
            foreach (array_chunk($insertSkuData, 100) as $partData) {
                DB::table('product_template_sku')->insert($partData);
            }
            foreach (array_chunk($insertSkuValueData, 100) as $partData) {
                DB::table('product_template_sku_value')->insert($partData);
            }
            foreach (array_chunk($insertGalleryData, 100) as $partData) {
                DB::table('product_template_gallery')->insert($partData);
            }
            DB::commit();

            return [
                'status' => 'successful',
                'result' => $template->id
            ];
        } catch (\Exception $ex) {
            DB::rollback();

            return [
                'status' => 'fail',
                'message' => $ex->getMessage() . ' Line: ' . $ex->getLine() . ' File: ' . $ex->getFile()
            ];
        }
    }

    public function getCategory($productId) {
        $pnc = DB::table('product_n_category')
            ->where('is_parent', 0)
            ->where('product_id', $productId)
            ->first();
        if (!$pnc) {
            return null;
        }

        $category = DB::table('category')
            ->where('id', $pnc->category_id)
            ->first(['id', 'name', 'slug']);
        return $category;
    }

    protected function buildProductImage($categorySlug, $options, $designType, $numOfDesign = 1, $imageIndex = false)
    {
        $retVal = '';
        $type = '';
        $style = '';
        $colorSlug = '';
        $color = '';
        $background = '';
        $hasSize = false;
        foreach ($options as $option) {
            $option['slug'] = strtolower($option['slug']);
            $option['variant_slug'] = strtolower($option['variant_slug']);
            if ($option['variant_slug'] == 'color') {
                $color = $this->getColor($option);
                if (!$color) {
                    break;
                }
                $colorSlug = trim($option['slug'], "-");
            } elseif (in_array($option['variant_slug'], ['type', 'model'])) {
                $type = trim($option['slug'], "-");
            } elseif (in_array($option['variant_slug'], ['style', 'shape', 'line-color'])) {
                $style = trim($option['slug'], "-");
            } if ($option['variant_slug'] == 'background' && !empty($option['image_url']) && $option['slug'] != 'transparent') {
                $background = DesignCodeUtil::designUrlToDesignCode($option['image_url']);
            } elseif ($option['variant_slug'] == 'size') {
                $hasSize = true;
            }
        }
        if (!$color && (($type || !$hasSize) && strpos($categorySlug, 'shirt') === false && strpos($categorySlug, 'hoodie') === false)) {
            $color = '#fff';
            $colorSlug = 'white';
        }
        if (!$color) {
            return null;
        }
        $imageSlug = ImageSlugUtil::getImageSlug($categorySlug, $options, $imageIndex);

        $designStr = [];
        for ($i = 0; $i < $numOfDesign; $i++) {
            $designStr[] = '[DESIGN_ID]';
        }

        $ingoreSlugs = $this->getIgnoreSlugs();

        if (in_array($imageSlug, $ingoreSlugs)) {
            return null;
        }

        $maxIndex = $this->getSlugMaxIndex($categorySlug, [$type, $style]);
        if ($maxIndex > 0 && $imageIndex > $maxIndex) {
            return null;
        }

        if ($designType == 'encrypted') {
            $retVal = config('pod::image.service_url') . '/image/' . $imageSlug . ',' . $colorSlug . ',assets,' . str_replace('#', '', $color) . '/' . implode('/', $designStr) . '.jpeg';
        } else {
            $retVal = config('pod::image.service_url') . '/image/' . $imageSlug . ',' . $colorSlug . ',[DESIGN_ID]' . ($background ? ':' . $background . ',' : ',') . str_replace('#', '', $color) . '.jpeg';
        }

        return $retVal;
    }

    private function getIgnoreSlugs() {
        $result = [
            "t-shirts-men-v-neck-t-shirt-3",
            "t-shirts-unisex-t-shirt-premium-a-col-en-v-unisexe-2",
            "t-shirts-unisex-t-shirt-premium-a-col-en-v-unisexe-3",
            "t-shirts-men-ringer-t-shirt-3",
            "t-shirts-unisex-t-shirt-unisexe-a-sonnerie-2",
            "t-shirts-unisex-t-shirt-unisexe-a-sonnerie-3",
            "t-shirts-men-tri-blend-t-shirt-2",
            "t-shirts-men-tri-blend-t-shirt-extra-soft-2",
            "t-shirts-unisex-t-shirt-a-col-rond-triblend-unisexe-2",
            "t-shirts-unisex-t-shirt-a-col-rond-triblend-unisexe-3",
            "t-shirts-men-tri-blend-t-shirt-3",
            "t-shirts-men-tri-blend-t-shirt-extra-soft-3",
        
            "t-shirts-women-v-neck-t-shirt-2",
            "t-shirts-femmes-t-shirt-premium-a-col-en-v-unisexe-2",
            "t-shirts-femmes-t-shirt-premium-a-col-en-v-unisexe-3",
            "t-shirts-women-v-neck-t-shirt-3",
            "t-shirts-femmes-t-shirt-unisexe-a-sonnerie-3",
            "t-shirts-women-ringer-t-shirt-3",
            "t-shirts-femmes-t-shirt-a-col-rond-triblend-unisexe-3",
            "t-shirts-women-tri-blend-t-shirt-extra-soft-2",
            "t-shirts-women-tri-blend-t-shirt-3",
            "t-shirts-women-tri-blend-t-shirt-extra-soft-3",
        
            "t-shirts-jeunes-t-shirt-classique-2",
            "t-shirts-jeunes-t-shirt-classique-3",
            "t-shirts-youth-2",
            "t-shirts-youth-t-shirt-2",
            "t-shirts-youth-3",
            "t-shirts-youth-t-shirt-3",
            "t-shirts-youth-heavyweight-t-shirt-2",
            "t-shirts-youth-heavyweight-t-shirt-3",
        
            "t-shirts-young-2",
            "t-shirts-young-t-shirt-2",
            "t-shirts-young-3",
            "t-shirts-young-t-shirt-3",
            "t-shirts-young-heavyweight-t-shirt-2",
            "t-shirts-young-heavyweight-t-shirt-3",

            "t-shirts-kids-2",
            "t-shirts-kids-t-shirt-2",
            "t-shirts-kids-3",
            "t-shirts-kids-t-shirt-3",
            "t-shirts-kids-heavyweight-t-shirt-2",
            "t-shirts-kids-heavyweight-t-shirt-3",

            //es
            "classic-t-shirts-ni-os-2",
            "classic-t-shirts-ni-os-3",
            "classic-t-shirts-ni-os-heavyweight-t-shirt-2",
            "classic-t-shirts-ni-os-heavyweight-t-shirt-3",
        ];

        return $result;
    }

    protected function getSlugMaxIndex($categorySlug, $optionSlugs) {
        $config = [
            [
                'category_slugs' => [
                    't-shirts',
                    't-shirt',
                    'classic-t-shirts',
                    'classic-t-shirt',
                ],
                'options' => [
                    [
                        'slugs' => ['v-neck'],
                        'max' => 2
                    ],
                    [
                        'slugs' => ['col-en-v-unisexe'],
                        'max' => 2
                    ],
                    [
                        'slugs' => ['women', 'classic'],
                        'max' => 3
                    ],
                    [
                        'slugs' => ['womens', 'classic'],
                        'max' => 3
                    ],
                    [
                        'slugs' => ['woman', 'classic'],
                        'max' => 3
                    ],
                    [
                        'slugs' => ['hombres', 'classic'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['hommes', 'classi'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['uomini', 'classic'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['bebe', 'classic'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['herren', 'classic'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['v-auschnitt'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['ko-t-shirt'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['t-shirt-bio'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['eco-t-shirt'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['ringer'],
                        'max' => 2
                    ],
                    [
                        'slugs' => ['a-sonnerie'],
                        'max' => 2
                    ],
                    [
                        'slugs' => ['triblend'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['tri-blend'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['kinder'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['enfant'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['youth', 'heavyweight-t-shirt'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['kids', 'heavyweight-t-shirt'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['youth', 'comfort-colors-tee'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['kids', 'comfort-colors-tee'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['bambini', 'heavyweight-t-shirt'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['ni-os', 'heavyweight-t-shirt'],
                        'max' => 1
                    ],
                    [
                        'slugs' => ['kids', 'baby-onesie'],
                        'max' => 2
                    ],
                    [
                        'slugs' => ['eco-t-shirt-recycled-organic'],
                        'max' => 1
                    ],
                ]
            ]
        ];

        $config[0]['options'][] = [
            'slugs' => ['classic'],
            'max' => 1
        ];

        $result = 0;

        foreach ($optionSlugs as $key => $value) {
            if (!$value) {
                unset($optionSlugs[$key]);
            }
        }

        foreach ($config as $item) {
            if (
                in_array($categorySlug, $item['category_slugs'])
            ) {
                foreach ($item['options'] as $option) {
                    $count = 0;
                    foreach ($option['slugs'] as $slug) {
                        foreach ($optionSlugs as $optionSlug) {
                            if (strpos($optionSlug, $slug) !== false) {
                                $count++;
                            }
                        }
                    }
                    if ($count == count($option['slugs'])) {
                        return $option['max'];
                    }
                }
            }
        }

        return $result;
    }

    private function storeTemplate($input) {
        $productId = $input['product_id'];
        $name = $input['name'];

        $template = ProductTemplate::where('product_id_fake', $productId)->first();
        $imageCount = !empty($input['image_count']) ? $input['image_count'] : 1;
        if (!$template) {
            $template = ProductTemplate::create([
                'product_id_fake' => $productId,
                'name' => $name,
                'image_per_sku' => $imageCount,
            ]);
        } else {
            $template->fill([
                'name' => $name,
                'product_id_fake' => $productId,
                'image_per_sku' => $imageCount,
            ]);
            $template->save();
        }

        return $template;
    }
}