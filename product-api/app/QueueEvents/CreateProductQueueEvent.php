<?php

namespace App\QueueEvents;

use App\Packages\Interfaces\IEvent;

class CreateProductQueueEvent implements IEvent
{
    public function __construct(
        public readonly array $data
    ) {

    }

    public function getQueueName(): string
    {
        return 'micro_pod_product_api_product';
    }

    public function getExchange(): string
    {
        return 'product.' . $this->getRoutingKey() . '.' . $this->getExchangeType();
    }

    public function getRoutingKey(): string
    {
        return 'create';
    }

    public function getExchangeType(): string
    {
        return 'fanout';
    }

    public function getData()
    {
        return $this->data;
    }
}