<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $service
    ) {}

    public function create(CreateCategoryRequest $request)
    {
        $data = $this->service->create(
            $request->validated()
        );

        return response()->success(
            $data,
            'Category created successfully',
            201
        );
    }

    public function read(Request $request)
    {
        $data = $this->service->readAll(
            $request->search
        );

        return response()->success(
            $data,
            'Categories fetched successfully'
        );
    }

    public function show($id)
    {
        $data = $this->service->read($id);

        return response()->success(
            $data,
            'Category detail fetched successfully'
        );
    }

    public function update(
        UpdateCategoryRequest $request,
        $id
    ) {
        $data = $this->service->update(
            $id,
            $request->validated()
        );

        return response()->success(
            $data,
            'Category updated successfully'
        );
    }

    public function delete($id)
    {
        $this->service->delete($id);

        return response()->success(
            null,
            'Category deleted successfully'
        );
    }
}