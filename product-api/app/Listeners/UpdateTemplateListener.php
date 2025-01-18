<?php

namespace App\Listeners;

use App\Packages\Utils\Utils;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;
use App\Packages\Queue\QueueService;
use App\Services\IProductTemplateService;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTemplateListener implements ShouldQueue
{
    public $queue = 'micro_pod_main_queue';
    public $exchagne = 'template.change.fanout';
    private QueueService $queueService;
    private IProductTemplateService $templateService;

    /**
     * Create the event listener.
     */
    public function __construct(
        QueueService $queueService, 
        IProductTemplateService $templateService
    ) {
        $this->queueService = $queueService;
        $this->templateService = $templateService;
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
        return;
        try {
            // \Log::info('CreateProductListener', [$message->getBody()]);
            \Log::info('UpdateTemplateListener product.changed.fanout handle');
            $data = Utils::parseMessageData($message->getBody());
            $response = $this->templateService->saveTemplate($data['data']);
            if ($response['status'] == 'successful') {
                \Log::info('UpdateTemplateListener publishing');
                $this->queueService->publishExchange('template.push-change.fanout', $response['result']);
            } else {
                Log::error('CreateProductListener product.changed.fanout', [$response]);
                $this->queueService->publishExchange('template.change-error.fanout', Utils::getPublishMessageData($data['user'], $response));
            }
            
            \Log::info('UpdateTemplateListener product.changed.fanout DONE HANDLE');
            $message->ack();
            //code...
        } catch (\Exception $ex) {
            \Log::error($ex);
            $message->ack();
        }
    }
}
