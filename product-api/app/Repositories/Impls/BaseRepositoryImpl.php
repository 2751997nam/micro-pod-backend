<?php

namespace App\Repositories\Impls;

use App\Repositories\IBaseRepository;
use Illuminate\Contracts\Database\Eloquent\Builder;

abstract class BaseRepositoryImpl implements IBaseRepository
{
    abstract public function getModel() : Builder;

    public function find(int | string $id) {
        return $this->getModel()->find($id);
    }

    public function findMany($ids) {
        return $this->getModel()->whereIn('id', $ids)->get();
    }

    public function create(array $args) {
        $retVal = $this->getModel()->create($args);
        return $retVal;
    }

    public function update(array $args) {
        $item = $this->find($args["id"]);
        if ($item) {
            $item->fill($args);
            $item->save();
            return $item;
        }
    }
    
    public function delete(int | string $id) {
        $item = $this->find($id);
        return $item->delete();
    }

    public function deleteMany($ids) {
        return $this->getModel()->whereIn('id', $ids)->delete();
    }
}