<?php

namespace App\Http\Controllers;

use App\Packages\Queue\QueueService;
use App\Queries\ITemplateQuery;

class TemplateController extends Controller
{
    private ITemplateQuery $templateQuery;
    private QueueService $queueService;

    public function __construct(
        ITemplateQuery $templateQuery,
        QueueService $queueService
    ) {
        $this->templateQuery = $templateQuery;
        $this->queueService = $queueService;
    }
    
    public function getData($id) {
        return [
            'status' => 'successful',
            'result' => $this->templateQuery->getData($id)
        ];
    }

    public function sendChangeEvent($id) {
        $this->queueService->publishExchange('template.push-change.fanout', [
            'id' => $id
        ]);

        return [
            'status' => 'successful'
        ];
    }
}
