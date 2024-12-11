<?php

namespace App\Repositories\Impls;

use App\Models\TagRefer;
use App\Repositories\ITagReferRepository;
use App\Repositories\Impls\BaseRepositoryImpl;
use Illuminate\Contracts\Database\Eloquent\Builder;

class TagReferRepositoryImpl extends BaseRepositoryImpl implements ITagReferRepository
{
    public function getModel() : Builder
    {
        return TagRefer::query();
    }

    public function deleteByReferTypeAndReferId(string $referType, int $referId)
    {
        $this->getModel()->where('refer_type', $referType)->where('refer_id', $referId)->delete();
    }
}