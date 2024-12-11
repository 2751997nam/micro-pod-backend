<?php

namespace App\Services\Impls;

use App\Repositories\ILogRepository;
use App\Services\ILogService;

class LogServiceImpl implements ILogService
{
    private ILogRepository $logRepo;
    public function __construct(ILogRepository $logRepo) {
        $this->logRepo = $logRepo;
    }
    public function writeLog($data, $targetId, $type, $event, $email)
    {
        $this->logRepo->getModel()->create([
            'actor_email' => $email,
            'target_id' => $targetId,
            'target_type' => $type,
            'event_type' => $event,
            'data' => json_encode($data),
            'created_at' => date('Y-m-d H:i:s', time()),
        ]);
    }

    public function writeProductLog($data, $productId, $event, $email)
    {
        $this->writeLog($data, $productId, 'PRODUCT', $event, $email);
    }
}