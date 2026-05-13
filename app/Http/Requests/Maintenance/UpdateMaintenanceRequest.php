<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_id' => [
                'sometimes',
                'exists:assets,id'
            ],

            'reported_by' => [
                'sometimes',
                'exists:users,id'
            ],

            'issue_description' => [
                'sometimes',
                'string'
            ],

            'maintenance_status' => [
                'sometimes',
                'in:pending,in_progress,completed,rejected'
            ],

            'reported_date' => [
                'sometimes',
                'date'
            ]
        ];
    }
}