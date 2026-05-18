<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PermissionResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'role_id'     => data_get($this, 'id'),
            'name'        => data_get($this, 'name'),
            'slug'        => data_get($this, 'slug'),
            'description' => data_get($this, 'description'),
            'is_active'   => data_get($this, 'is_active'),
            'created_at'  => data_get($this, 'created_at'),
            'updated_at'  => data_get($this, 'updated_at'),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
