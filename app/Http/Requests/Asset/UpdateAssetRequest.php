<?php

namespace App\Http\Requests\Asset;

use App\Models\Asset;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
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
        /** @var Asset $asset */
        $asset = $this->attributes->get('asset');

        return [
            'asset_name'     => ['sometimes', 'string', 'max:150'],
            'asset_code'     => ['sometimes', 'string', 'max:50', 'unique:assets,asset_code,' . ($asset?->id ?? 'NULL')],
            'category_id'    => ['sometimes', 'integer', 'exists:categories,id'],
            'department_id'  => ['sometimes', 'integer', 'exists:departments,department_id'],
            'brand'          => ['nullable', 'string', 'max:100'],
            'purchase_date'  => ['nullable', 'date', 'before_or_equal:today'],
            'total_quantity' => [
                'sometimes',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($asset) {
                    if ($asset) {
                        $assignedQty = $asset->total_quantity - $asset->remaining_quantity;
                        if ($value < $assignedQty) {
                            $fail("Total quantity cannot be less than already assigned units ({$assignedQty}).");
                        }
                    }
                },
            ],
            'remaining_quantity' => [
                'sometimes',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($asset) {
                    if ($asset) {
                        $newTotal = $this->input('total_quantity', $asset->total_quantity);
                        if ($value > $newTotal) {
                            $fail("Remaining quantity cannot exceed total quantity ({$newTotal}).");
                        }
                    }
                },
            ],
            'status' => ['sometimes', 'string', 'in:' . implode(',', Asset::STATUSES)],
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
            'asset_code.unique'      => 'This asset code is already taken by another asset.',
            'category_id.exists'     => 'The selected category does not exist.',
            'department_id.exists'   => 'The selected department does not exist.',
            'status.in'              => 'Status must be one of: ' . implode(', ', Asset::STATUSES) . '.',
        ];
    }
}
