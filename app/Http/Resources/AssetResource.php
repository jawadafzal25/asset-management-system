<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * AssetResource
 *
 * Transforms the Asset model into a clean, standardised API response structure.
 */
class AssetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'assetName'         => $this->asset_name,
            'assetCode'         => $this->asset_code,

            'category'          => $this->whenLoaded('category', fn() => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ]),
            'department'        => $this->whenLoaded('department', fn() => [
                'id'   => $this->department->id,
                'name' => $this->department->name,
            ]),

            'categoryId'        => $this->category_id,
            'departmentId'      => $this->department_id,

            'brand'             => $this->brand,
            'purchaseDate'      => $this->purchase_date?->toDateString(),

            'totalQuantity'     => $this->total_quantity,
            'remainingQuantity' => $this->remaining_quantity,
            'assignedQuantity'  => $this->assigned_quantity,

            'status'            => $this->status,

            'createdAt'         => $this->created_at?->toISOString(),
            'updatedAt'         => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Format a paginated collection for use with the global success response.
     */
    public static function collectionWithMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'list' => self::collection($paginator->items()),
            'meta' => [
                'currentPage' => $paginator->currentPage(),
                'perPage'     => $paginator->perPage(),
                'total'       => $paginator->total(),
                'lastPage'    => $paginator->lastPage(),
            ],
        ];
    }
}
