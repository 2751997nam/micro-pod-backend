<?php

namespace App\Packages\Log;

use Monolog\Formatter\ElasticsearchFormatter;
use Monolog\LogRecord;

class LogFormatter extends ElasticsearchFormatter {
    protected function getDocument(array $record): array
    {
        $record['message'] = config('app.name') . ' | ' . $record['message'];
        $record['_index'] = $this->index;
        $record['_type'] = $this->type;

        return $record;
    }
}