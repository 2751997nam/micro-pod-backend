<?php

return [
    App\Providers\AppServiceProvider::class,
    \App\Packages\Providers\QueueServiceProvider::class,
    Elasticsearch\ClientServiceProvider::class,
];
