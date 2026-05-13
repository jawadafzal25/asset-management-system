<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\RoleResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'role'              => $this->role,
            'role_id'           => $this->role_id,
            'assigned_role'     => new RoleResource($this->whenLoaded('assignedRole')),
            'profile_picture'   => $this->profile_picture ? Storage::disk('public')->url($this->profile_picture) : null,
            'organization_id'   => $this->organization_id,
            'organization'      => $this->when($this->organization, OrganizationResource::make($this->organization)),
            'is_active'         => $this->is_active,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}