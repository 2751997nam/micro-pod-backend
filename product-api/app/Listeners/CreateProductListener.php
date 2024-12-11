<?php

namespace App\Listeners;

use App\Packages\Queue\QueueService;
use App\Packages\Utils\Utils;
use App\Queries\IProductQuery;
use App\Services\IProductService;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateProductListener implements ShouldQueue
{
    public $queue = 'product.create.fanout';
    private QueueService $queueService;
    private IProductService $productService;
    private IProductQuery $productQuery;

    /**
     * Create the event listener.
     */
    public function __construct(
        QueueService $queueService, 
        IProductService $productService,
        IProductQuery $productQuery
    ) {
        $this->queueService = $queueService;
        $this->productService = $productService;
        $this->productQuery = $productQuery;
    }

    public function getQueueName() {
        return $this->queue;
    }
    /**
     * Handle the event.
     */
    public function handle(AMQPMessage $message): void
    {
        // \Log::info('CreateProductListener', [$message->getBody()]);

        $data = Utils::parseMessageData($message->getBody());
        $response = $this->productService->saveProduct($data);
        if ($response['status'] == 'successful') {
            $this->queueService->publishExchange('product.changed.fanout', Utils::getPublishMessageData($data['user'], $this->productQuery->getData($response['result'])));
        } else {
            $this->queueService->publishExchange('product.change-error.fanout', Utils::getPublishMessageData($data['user'], $response));
        }

        $message->ack();
    }
}
