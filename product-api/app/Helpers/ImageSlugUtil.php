<?php

namespace App\Helpers;

class ImageSlugUtil
{
    public static function getImageSlug($categorySlug, $options, $imageIndex = false)
    {
        $slugs = [strtolower($categorySlug)];
        $type = '';
        $style = '';
        $sizes = '';
        $sizeSlugs = ['sizes', 'mug-size'];
        foreach ($options as $option) {
            $option = (array) $option;
            $option['slug'] = strtolower($option['slug']);
            $option['variant_slug'] = strtolower($option['variant_slug']);
            if (in_array($option['variant_slug'], ['type', 'model'])) {
                $type = trim($option['slug'], "-");
            } elseif (in_array($option['variant_slug'], ['style', 'shape', 'line-color'])) {
                $style = trim($option['slug'], "-");
            } elseif (in_array($option['variant_slug'], $sizeSlugs)) {
                $sizes = trim($option['slug'], "-");
            }
        }

        if ($type) {
            $slugs[] = $type;
        }
        if ($style) {
            $slugs[] = $style;
        }
        if ($sizes) {
            $slugs[] = $sizes;
        }
        if (is_numeric($imageIndex)) {
            $slugs[] = $imageIndex;
        }

        return implode('-', $slugs);
    }
}