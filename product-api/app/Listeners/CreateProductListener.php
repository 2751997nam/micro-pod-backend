<?php

namespace App\Listeners;

use App\Packages\Utils\Utils;
use App\Queries\IProductQuery;
use App\Services\IProductService;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;
use App\Packages\Queue\QueueService;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateProductListener implements ShouldQueue
{
    public $queue = 'micro_pod_product_api_product';
    public $exchagne = 'product.create.fanout';
    private QueueService $queueService;
    private IProductService $productService;

    /**
     * Create the event listener.
     */
    public function __construct(
        QueueService $queueService, 
        IProductService $productService
    ) {
        $this->queueService = $queueService;
        $this->productService = $productService;
    }

    public function getQueueName() {
        return $this->queue;
    }

    public function getExchange() {
        return $this->exchagne;
    }
    /**
     * Handle the event.
     */
    public function handle(AMQPMessage $message): void
    {
        try {
            // \Log::info('CreateProductListener', [$message->getBody()]);
            \Log::info('CreateProductListener product.changed.fanout handle');
            $data = Utils::parseMessageData($message->getBody());
            $response = $this->productService->saveProduct($data);
            if ($response['status'] == 'successful') {
                \Log::info('CreateProductListener publishing');
                $this->queueService->publishExchange('product.push-change.fanout', $response['result']);
            } else {
                Log::error('CreateProductListener product.changed.fanout', [$response]);
                $this->queueService->publishExchange('product.change-error.fanout', Utils::getPublishMessageData($data['user'], $response));
            }
            
            \Log::info('CreateProductListener product.changed.fanout DONE HANDLE');
            $message->ack();
            //code...
        } catch (\Exception $ex) {
            \Log::error($ex);
        }
    }
}
