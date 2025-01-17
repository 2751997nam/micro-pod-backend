<?php

namespace App\Packages\Queue;

use App\Packages\DTO\EventDTO;
use App\Packages\Interfaces\IEvent;
use App\Packages\Utils\Utils;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class QueueService {
    private $connections;

    public function __construct() {
        $this->connections = [];
    }

    public function connect($queueName) {
        if (!isset($this->connections[$queueName])) {
            $this->connections[$queueName] = $this->createConnection();
            \Log::info('current connections', array_keys($this->connections));
        }
    }
    
    public function createConnection() {
        $config = config('queue.connections.rabbitmq');
        $host = $config['hosts'][0];
        return new AMQPStreamConnection(
            $host['host'], 
            $host['port'], 
            $host['user'], 
            $host['password'], 
            $host['vhost'],
            false,
            'AMQPLAIN',
            null,
            'en_US',
            30.0,
            30.0
        );
    }

    public function getConsumeChannel(IEvent $event): AMQPChannel {
        $this->connect($event->getQueueName());
        \Log::info('getConsumeChannel connected', [$event->getQueueName(), array_keys($this->connections)]);
        $channel = $this->getConnection($event->getQueueName())->channel();
        \Log::info('getConsumeChannel get channel done', [$event->getQueueName()]);
        $queueName = $event->getQueueName();
        $channel->queue_declare($queueName, false, true, false, false);

        $channel->exchange_declare($event->getExchange(), $event->getExchangeType(), false, true, false);

        $channel->queue_bind($queueName, $event->getExchange(), $event->getRoutingKey());

        $channel->basic_qos(null, 10, null);
        \Log::info('getConsumeChannel DONE');
        return $channel;
    }

    public function getChannel($queueName): AMQPChannel {
        return $this->getConnection($queueName)->channel();
    }

    public function getConnection($queueName): AMQPStreamConnection {
        return $this->connections[$queueName];
    }

    public function getPublishChannel(IEvent $event): AMQPChannel {
        $this->connect('publish');
        $channel = $this->getConnection('publish')->channel();
        $channel->exchange_declare($event->getExchange(), $event->getExchangeType(), false, true, false);

        return $channel;
    }

    public function publishEvent(IEvent $event)
    {
        $messageBody = json_encode($event->getData());
        $channel = $this->getPublishChannel($event);
        $msg = new AMQPMessage($messageBody, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        
        $channel->basic_publish($msg, $event->getExchange(), $event->getRoutingKey());

        \Log::info('publishEvent basic_publish done', [$event->getExchange(), $event->getRoutingKey(), $messageBody]);

        $channel->close();
    }

    public function publishExchange($exchange, $data) {
        $event = $this->parseExchange($exchange);
        $event->setData($data);

        $this->publishEvent($event);
    }

    public function consumeEvent(IEvent $event, $callback) {
        $channel = $this->getConsumeChannel($event);

        $queueName = $event->getQueueName();
        $channel->basic_consume($queueName, '', false, false, false, false, $callback);
        try {
            $channel->consume();
        } catch (\Exception $ex) {
            \Log::error($ex);
        }
    }

    public function consumeExchange($exchange, $queueName, $callback) {
        $event = $this->parseExchange($exchange, $queueName);

        \Log::info('consumeExchange', [$event]);

        $this->consumeEvent($event, $callback);
    }

    public function parseExchange($exchange, $queueName = ''): IEvent {
        $retVal = new EventDTO();
        list($tmp, $routingKey, $exchangeType) = explode('.', $exchange);
        $retVal->setQueueName($queueName)
            ->setExchange($exchange)
            ->setRoutingKey($routingKey)
            ->setExchangeType($exchangeType);
            
        return $retVal;
    }
}