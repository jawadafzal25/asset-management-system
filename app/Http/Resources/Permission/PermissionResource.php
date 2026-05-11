<?php

namespace App\Http\Resources\Permission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /*
         * data_get($source, 'dot.path', $default)
         *
         * Instead of:  $this->permission_id
         * We write:    data_get($this, 'permission_id')
         *
         * This works on objects AND arrays, handles null safely,
         * and is consistent with how the whole team accesses data.
         */

        return [
            'permission_id'   => data_get($this, 'permission_id'),
            'permission_name' => data_get($this, 'permission_name'),
            'module'          => data_get($this, 'module'),
            'action'          => data_get($this, 'action'),
            'display_name'    => data_get($this, 'display_name'),
            'description'     => data_get($this, 'description'),
            'created_at'      => data_get($this, 'created_at'),
        ];
    }
}
