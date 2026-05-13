<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        return [

            'id' => data_get($this, 'id'),

            'asset_id' => data_get(
                $this,
                'asset_id'
            ),

            'reported_by' => data_get(
                $this,
                'reported_by'
            ),

            'issue_description' => data_get(
                $this,
                'issue_description'
            ),

            'maintenance_status' => data_get(
                $this,
                'maintenance_status'
            ),

            'reported_date' => data_get(
                $this,
                'reported_date'
            ),

            'created_at' => data_get(
                $this,
                'created_at'
            ),

            'updated_at' => data_get(
                $this,
                'updated_at'
            ),
        ];
    }
}