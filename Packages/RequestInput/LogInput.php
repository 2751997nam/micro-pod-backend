<?php

namespace App\Packages\RequestInput;

use App\Packages\Traits\JsonTrait;
use App\Packages\Traits\ObjectTrait;

class LogInput {
    use ObjectTrait;
    use JsonTrait;
    
    private $actorType;
    private $actorEmail;
    private $targetType;
    private $targetId;
    private $eventType;
    private $data;
    private $createdAt;

    public function getActorType()
    {
        return $this->actorType;
    }

    public function setActorType($actorType)
    {
        $this->actorType = $actorType;
    }

    public function getActorEmail()
    {
        return $this->actorEmail;
    }

    public function setActorEmail($actorEmail)
    {
        $this->actorEmail = $actorEmail;
    }

    public function getTargetType()
    {
        return $this->targetType;
    }

    public function setTargetType($targetType)
    {
        $this->targetType = $targetType;
    }

    public function getTargetId()
    {
        return $this->targetId;
    }

    public function setTargetId($targetId)
    {
        $this->targetId = $targetId;
    }

    public function getEventType()
    {
        return $this->eventType;
    }

    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}