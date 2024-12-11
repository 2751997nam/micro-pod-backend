<?php

namespace App\Queries;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryParentQuery
{
    private $categoryId;

    public function __construct(int $categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function getData(): Collection
    {
        return $this->getParents($this->categoryId);
    }

    /**
     * Recursively get all parents of a category
     *
     * @param int $parentId
     * @param Collection $result
     * @return Collection
     */
    private function getParents($parentId, Collection &$result = collect()) : Collection {
        $cate = Category::where('id', $parentId)->first();
        $result->push($cate);
        if ($cate->parent_id != 0) {
            $this->getParents($cate->parent_id, $result);
        }

        return $result;
    }
}