<?php

namespace App\Listeners;

use PhpAmqpLib\Message\AMQPMessage;
use App\Packages\Queue\QueueService;
use App\Listeners\CreateProductListener;
use App\Listeners\PushChangeProductListener;
use Swoole\Coroutine\Channel;
use Swoole\Coroutine;
use function Swoole\Coroutine\run;
use function Swoole\Coroutine\go;
class ListenerManager
{
    private $listeners = [];

    public function __construct()
    {
        $this->listeners = [
            'createProductListener' => CreateProductListener::class,
            'pushChangeProductListener' => PushChangeProductListener::class
        ];
    }

    public function listen() {
        run(function () {
            foreach ($this->listeners as $key => $value) {
                go(function() use ($key, $value) {
                    $obj = app()->make($value);
                    \Log::info('listening ' . $key);
                    $queueService = app()->make(QueueService::class);
                    $queueService->consumeExchange($obj->getExchange(), $obj->getQueueName(), function (AMQPMessage $message) use ($obj) {
                        \Log::info('has message ' . $obj->getExchange());
                        try {
                            $obj->handle($message);
                        } catch (\Exception $ex) {
                            \Log::error($obj->getExchange(), [$ex]);
                        }
                    });
                    \Log::info('done listen ' . $key);
                });
            }
        });
    }
}