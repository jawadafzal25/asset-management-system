<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'assignment_id' => $this->assignment_id,
            'asset_id'      => $this->asset_id,
            'employee_id'   => $this->employee_id,
            'assigned_by'   => $this->assigned_by,
            'quantity'      => $this->quantity,
            'status'        => $this->status,
            'assign_date'   => $this->assign_date, 
            'return_date'   => $this->return_date, 
            'is_active'     => is_null($this->return_date),
        ];
    }
}