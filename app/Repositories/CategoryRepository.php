<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function readAll($search = null)
    {
        return Category::query()
            ->when(
                $search !== null && $search !== '',
                fn($q) =>
            $q->where('name', 'like', "%{$search}%")
        )
        ->latest()
        ->paginate(10);
}
    public function read($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($category, array $data)
    {
        $category->update($data);
        return $category->fresh();
    }

    public function delete($category)
    {
        return $category->delete();
    }
}