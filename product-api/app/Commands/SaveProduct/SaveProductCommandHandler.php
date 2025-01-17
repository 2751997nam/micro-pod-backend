<?php

namespace App\Commands\SaveProduct;

use App\Commands\ICommandHandler;
use App\Commands\SaveProduct\SaveProductCommand;
use App\Envelop;
use App\Services\IProductService;

class SaveProductCommandHandler implements ICommandHandler
{
    private IProductService $productService;
    public function __construct(IProductService $productService) {
        $this->productService = $productService;
    }
    public function __invoke(SaveProductCommand $command) : Envelop
    {
    	$res = $this->productService->saveProduct($command->get('input'));

        return new Envelop($res);
    }
}