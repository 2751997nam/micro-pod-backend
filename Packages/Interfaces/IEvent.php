<?php

namespace App\Packages\Interfaces;

interface IEvent {
    public function getQueueName(): string;
    public function getRoutingKey(): string;
    public function getExchangeType(): string;
    public function getData();
}