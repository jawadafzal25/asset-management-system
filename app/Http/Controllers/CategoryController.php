<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $service
    ) {}

    public function create(CreateCategoryRequest $request)
    {
        $validated = $request->validated();

        $data = $this->service->create([
            'name' => data_get($validated, 'name'),
            'description' => data_get($validated, 'description'),
            'status' => data_get($validated, 'status'),
        ]);

        return response()->success(
            new CategoryResource($data),
            'Category created successfully',
            201
        );
    }

    public function read(Request $request)
    {
        $data = $this->service->readAll(
            data_get($request, 'search')
        );

        return response()->success(
            CategoryResource::collection($data),
            'Categories fetched successfully'
        );
    }

    public function show($id)
    {
        $data = $this->service->read($id);

        return response()->success(
            new CategoryResource($data),
            'Category detail fetched successfully'
        );
    }

    public function update(
        UpdateCategoryRequest $request,
        $id
    ) {
        $validated = $request->validated();

        $data = $this->service->update(
            $id,
            [
                'name' => data_get($validated, 'name'),
                'description' => data_get($validated, 'description'),
                'status' => data_get($validated, 'status'),
            ]
        );

        return response()->success(
            new CategoryResource($data),
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
