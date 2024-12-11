<?php

namespace App\Commands\SaveProduct;

use App\Commands\ICommand;
use App\Packages\Traits\ObjectTrait;
use Illuminate\Support\Str;

class SaveProductCommand implements ICommand {
    use ObjectTrait;

    private array $input;
    
    public function __construct(array $input)
    {
        $this->input = $input;
    }
}