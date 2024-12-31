<?php

namespace App\Http\Controllers;

use App\Queries\IProductQuery;
use App\Packages\Queue\QueueService;

class ProductController extends Controller
{
    private IProductQuery $productQuery;
    private QueueService $queueService;

    public function __construct(
        IProductQuery $productQuery,
        QueueService $queueService
    ) {
        $this->productQuery = $productQuery;
        $this->queueService = $queueService;
    }
    
    public function getData($id) {
        return [
            'status' => 'successful',
            'result' => $this->productQuery->getData($id)
        ];
    }

    public function sendChangeEvent($id) {
        $this->queueService->publishExchange('product.push-change.fanout', $id);

        return [
            'status' => 'successful'
        ];
    }
}
