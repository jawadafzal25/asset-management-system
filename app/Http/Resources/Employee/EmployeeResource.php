<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Department\DepartmentResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'employee_id'   => $this->employee_id,
            'name'          => $this->name,
            'father_name'   => $this->father_name,
            'contact'       => $this->contact_info,
            'email'         => $this->email,
            'address'       => $this->address,
            'designation'   => $this->designation,
            'joining_date'  => $this->joining_date,
            'salary'        => (float) $this->salary, // Cast to float for JSON numbers
            'status'        => $this->status,

            // Nested Relationship:
            // This only shows if you use 'with("department")' in your Service/Middleware
            'department'    => new DepartmentResource($this->whenLoaded('department')),

            'created_at'    => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
