<?php

namespace App\Repositories\Impls;

use App\Models\LogModel;
use App\Repositories\ILogRepository;
use App\Repositories\Impls\BaseRepositoryImpl;
use Illuminate\Contracts\Database\Eloquent\Builder;

class LogRepositoryImpl extends BaseRepositoryImpl implements ILogRepository
{
    public function getModel() : Builder
    {
        return LogModel::query();
    }
}