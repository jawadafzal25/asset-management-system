<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => data_get($this, 'id'),
            'name' => data_get($this, 'name'),
            'description' => data_get($this, 'description'),
            'status' => data_get($this, 'status'),
            'created_at' => data_get($this, 'created_at')?->toDateTimeString(),
            'updated_at' => data_get($this, 'updated_at')?->toDateTimeString(),
        ];
    }
}
