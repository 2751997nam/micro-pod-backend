<?php

namespace App\Queries;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryTreeQuery
{
    private $categoryId;

    public function __construct(int $categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function getData(): Collection
    {
        $categories = $this->getParents($this->categoryId);

        $categories->push(Category::where('id', $this->categoryId)->first());
        $categories->orderBy('_lft', 'asc');

        return $categories;
    }
}