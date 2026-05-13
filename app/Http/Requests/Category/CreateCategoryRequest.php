<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('categories', 'name')
                    ->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string',
            'status' => 'boolean'
        ];
    }
}
