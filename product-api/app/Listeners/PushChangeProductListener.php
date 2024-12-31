<?php

namespace App\Listeners;

use App\Packages\Utils\Utils;
use App\Queries\IProductQuery;
use PhpAmqpLib\Message\AMQPMessage;
use App\Packages\Queue\QueueService;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushChangeProductListener implements ShouldQueue
{
    public $queue = 'micro_pod_product_api_push_change';
    public $exchagne = 'product.push-change.fanout';
    private QueueService $queueService;
    private IProductQuery $productQuery;

    /**
     * Create the event listener.
     */
    public function __construct(
        QueueService $queueService, 
        IProductQuery $productQuery
    ) {
        $this->queueService = $queueService;
        $this->productQuery = $productQuery;
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
            \Log::info('PushChangeProductListener product.push-change.fanout handle');
            \Log::info('PushChangeProductListener message', [$message->getBody()]);
            $messageData = Utils::getPublishMessageData(null, $this->productQuery->getData($message->getBody()));
            $this->queueService->publishExchange('product.changed.fanout', $messageData);
            \Log::info('PushChangeProductListener product.push-change.fanout DONE HANDLE');
            $message->ack();
            //code...
        } catch (\Exception $ex) {
            \Log::error($ex);
        }
    }
}
