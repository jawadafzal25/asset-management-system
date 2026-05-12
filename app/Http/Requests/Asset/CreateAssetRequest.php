<?php

namespace App\Http\Requests\Asset;

use App\Models\Asset;
use Illuminate\Foundation\Http\FormRequest;

class CreateAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'asset_name'     => ['required', 'string', 'max:150'],
            'asset_code'     => ['required', 'string', 'max:50', 'unique:assets,asset_code'],
            'category_id'    => ['required', 'integer', 'exists:categories,id'],
            'department_id'  => ['required', 'integer', 'exists:departments,department_id'],
            'brand'          => ['nullable', 'string', 'max:100'],
            'purchase_date'  => ['nullable', 'date', 'before_or_equal:today'],
            'total_quantity' => ['required', 'integer', 'min:1'],
            'status'         => ['nullable', 'string', 'in:' . implode(',', Asset::STATUSES)],
            'asset_image'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'invoice_image'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'asset_code.unique'      => 'This asset code is already taken. Please use a unique code.',
            'category_id.exists'     => 'The selected category does not exist.',
            'department_id.exists'   => 'The selected department does not exist.',
            'total_quantity.min'     => 'Total quantity must be at least 1.',
            'status.in'              => 'Status must be one of: ' . implode(', ', Asset::STATUSES) . '.',
        ];
    }
}
