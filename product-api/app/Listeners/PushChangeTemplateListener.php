<?php

namespace App\Listeners;

use App\Packages\Utils\Utils;
use PhpAmqpLib\Message\AMQPMessage;
use App\Packages\Queue\QueueService;
use App\Queries\ITemplateQuery;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushChangeTemplateListener implements ShouldQueue
{
    public $queue = 'micro_pod_main_queue';
    public $exchagne = 'template.push-change.fanout';
    private QueueService $queueService;
    private ITemplateQuery $templateQuery;

    /**
     * Create the event listener.
     */
    public function __construct(
        QueueService $queueService, 
        ITemplateQuery $templateQuery
    ) {
        $this->queueService = $queueService;
        $this->templateQuery = $templateQuery;
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
            \Log::info('PushChangeTemplateListener template.push-change.fanout handle');
            \Log::info('PushChangeTemplateListener message', [$message->getBody()]);
            $messageData = Utils::getPublishMessageData(null, $this->templateQuery->getData(intval($message->getBody())));
            $this->queueService->publishExchange('federate_template.changed.fanout', $messageData);
            \Log::info('PushChangeTemplateListener template.push-change.fanout DONE HANDLE');
            $message->ack();
            //code...
        } catch (\Exception $ex) {
            \Log::error($ex);
        }
    }
}
