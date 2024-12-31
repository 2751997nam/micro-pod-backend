<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Listeners\ListenerManager;

class ConsumeQueueCommand extends Command
{
    private ListenerManager $listenerManager;

    public function __construct(ListenerManager $listenerManager)
    {
        parent::__construct();
        $this->listenerManager = $listenerManager;
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
        set_time_limit(0);
        \Log::info('Consumming queue');

        $this->listenerManager->listen();

        \Log::info('done Consumm queue');
    }
}
