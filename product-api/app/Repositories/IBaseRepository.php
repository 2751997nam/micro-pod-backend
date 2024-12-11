<?php

namespace App\Repositories;

use Illuminate\Contracts\Database\Eloquent\Builder;

interface IBaseRepository {
    public function getModel() : Builder;

    public function find(int | string $id);
    public function findMany($ids);
    public function create(array $args);
    public function update(array $args);
    public function delete(int | string $id);
    public function deleteMany($ids);
}