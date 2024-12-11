<?php

namespace App;

use App\Commands\ICommand;
use Illuminate\Support\Facades\App;
use ReflectionClass;

class CommandBus
{
    public function handle(ICommand $command) : Envelop
    {
        // resolve handler
        $reflection = new ReflectionClass($command);
        $handlerName = str_replace("Command", "Handler", $reflection->getShortName());
        $handlerName = str_replace($reflection->getShortName(), $handlerName, $reflection->getName());
        $handler = App::make($handlerName);
        // invoke handler
        return $handler($command);
    }

    public function handleMultiple(array $commands)
    {
        foreach ($commands as $command) {
            $this->handle($command);
        }
    }
}