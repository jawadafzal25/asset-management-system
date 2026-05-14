<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class CreateMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_id' => [
                'required',
                'exists:assets,id'
            ],

            'reported_by' => [
                'required',
                'exists:users,id'
            ],

            'issue_description' => [
                'required',
                'string'
            ],

            'maintenance_status' => [
                'required',
                'in:pending,in_progress,completed,rejected'
            ],

            'reported_date' => [
                'required',
                'date'
            ]
        ];
    }
}