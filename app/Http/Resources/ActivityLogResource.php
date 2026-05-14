<?php

namespace App\Http\Resources\Log;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => data_get($this, 'id'),
            'action'       => data_get($this, 'action'),
            'module'       => data_get($this, 'module'),
            'status'       => data_get($this, 'status'),

            'entity' => [
                'type' => data_get($this, 'entity_type'),
                'id'   => data_get($this, 'entity_id'),
            ],

            'changes' => [
                'old' => data_get($this, 'old_values'),
                'new' => data_get($this, 'new_values'),
            ],

            'performed_by' => [
                'user_id' => data_get($this, 'user_id'),
                'name'    => data_get($this, 'user_name'),
                'email'   => data_get($this, 'user_email'),
            ],

            'request' => [
                'ip_address' => data_get($this, 'ip_address'),
                'user_agent' => data_get($this, 'user_agent'),
                'url'        => data_get($this, 'url'),
                'method'     => data_get($this, 'method'),
            ],

            'error_message' => data_get($this, 'error_message'),
            'timestamp'     => data_get($this, 'created_at'),
        ];
    }
}
