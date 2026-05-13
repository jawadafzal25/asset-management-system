<?php

namespace App\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Schema;
use App\Repositories\CategoryRepository;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $repo
    ) {}

    public function readAll($search = null)
    {
        return $this->repo->readAll($search);
    }

    public function read($id)
    {
        return $this->repo->read($id);
    }

    public function create($data)
    {
        return $this->repo->create($data);
    }

    public function update($id, $data)
    {
        $category = $this->repo->read($id);

        return $this->repo->update($category, $data);
    }

    public function delete($id)
    {
        $category = $this->repo->read($id);

        if (
            class_exists(\App\Models\Asset::class) &&
            Schema::hasTable('assets') &&
            $category->assets()->exists()
        ) {
            throw new AuthorizationException(
                'Cannot delete category because assets exist'
            );
        }

        return $this->repo->delete($category);
    }
}
