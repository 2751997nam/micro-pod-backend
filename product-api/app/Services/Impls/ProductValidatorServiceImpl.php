<?php

namespace App\Services\Impls;

use Illuminate\Http\Request;
use App\Services\IProductValidatorService;

class ProductValidatorServiceImpl implements IProductValidatorService {
    public function validate(array $input) {
        $retVal = false;
        $name = !empty($input['name']) ? $input['name'] : '';
        if (!$name) {
            return 'Product title is requied';
        } elseif (strlen($name) > 500) {
            return 'Product title should not be greater than 500 characters';
        }

        if (empty($input['brand_id'])) {
            return 'Brand name is required';
        }

        return $retVal;
    }

}