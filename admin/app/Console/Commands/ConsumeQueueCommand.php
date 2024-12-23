<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;
use App\Packages\Queue\QueueService;

class ConsumeQueueCommand extends Command
{
    private QueueService $queueService;

    public function __construct(QueueService $queueService)
    {
        parent::__construct();
        $this->queueService = $queueService;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        \Log::info('Consumming queue');
        
        $this->queueService->consumeExchange('product.create.fanout', 'micro_pod_admin_product', function (AMQPMessage $message) {
            \Log::info('product.create $message', [$message->getBody()]);
            $message->ack();
        });
    }
}
