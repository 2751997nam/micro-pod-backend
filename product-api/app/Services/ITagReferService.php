<?php

namespace App\Services;

interface ITagReferService
{
    public function storeTagRefer(int $targetId, string $targetType, array $tagIds);
}