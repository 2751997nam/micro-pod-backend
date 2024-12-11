<?php

namespace App\Services\Impls;

use App\Services\ITagReferService;
use App\Repositories\ITagReferRepository;

class TagReferServiceImpl implements ITagReferService
{
    protected ITagReferRepository $tagReferRepo;
    
    public function __construct(ITagReferRepository $tagReferRepo) {
        $this->tagReferRepo = $tagReferRepo;
    }

    public function storeTagRefer(int $targetId, string $targetType, array $tagIds) {

    }
}