<?php

namespace App\Packages\DTO;

use App\Packages\Interfaces\IEvent;
use App\Packages\Traits\JsonTrait;

class EventDTO implements IEvent
{

    use JsonTrait;
    public string $queueName;
    public string $exchange;
    public string $routingKey;
    public string $exchangeType;
    public $data;

    /**
     * Get the value of exchange
     */
    public function getQueueName(): string
    {
        return $this->queueName;
    }

    /**
     * Set the value of exchange
     */
    public function setQueueName(string $queueName): self
    {
        $this->queueName = $queueName;

        return $this;
    }

    public function getExchange(): string
    {
        return $this->exchange;
    }

    public function setExchange(string $exchange): self
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * Get the value of routingKey
     */
    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }

    /**
     * Set the value of routingKey
     */
    public function setRoutingKey(string $routingKey): self
    {
        $this->routingKey = $routingKey;

        return $this;
    }

    /**
     * Get the value of exchangeType
     */
    public function getExchangeType(): string
    {
        return $this->exchangeType;
    }

    /**
     * Set the value of exchangeType
     */
    public function setExchangeType(string $exchangeType): self
    {
        $this->exchangeType = $exchangeType;

        return $this;
    }

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of data
     */
    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }
}
