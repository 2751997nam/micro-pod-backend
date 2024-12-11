<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Message\AMQPMessage;
use App\Packages\Queue\QueueService;
use App\Listeners\CreateProductListener;

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

        $this->queueService->consumeExchange('product.create.fanout', function (AMQPMessage $message) {
            \Log::info('has message product.create.fanout');
            try {
                app()->make(\App\Listeners\CreateProductListener::class)->handle($message);
            } catch (\Exception $ex) {
                \Log::error($ex);
            }
        });
    }
}
