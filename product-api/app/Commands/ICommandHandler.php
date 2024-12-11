<?php

namespace App\Commands;

interface ICommandHandler {
    public function __invoke(ICommand $command) : Envelop;
}