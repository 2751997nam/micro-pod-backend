<?php

namespace App\Repositories;

use App\Repositories\IBaseRepository;

interface ITagReferRepository extends IBaseRepository {

    public function deleteByReferTypeAndReferId(string $referType, int $referId);
}