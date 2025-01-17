<?php

namespace App\Queries;

use App\Models\ProductTemplate;

interface ITemplateQuery
{
    public function getData(int $productId): ProductTemplate;
}