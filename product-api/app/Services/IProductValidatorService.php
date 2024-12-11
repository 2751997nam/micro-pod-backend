<?php

namespace App\Services;

interface IProductValidatorService
{
    public function validate(array $input);
}