<?php

namespace App\Services;

interface ILogService
{
    public function writeLog($data, $targetId, $type, $event, $email);
    public function writeProductLog($data, $productId, $event, $email);
}