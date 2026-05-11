<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * AssetService
 *
 * Encapsulates all business logic for the Asset module.
 */
class AssetService
{
    /**
     * Get a paginated and filtered list of assets.
     */
    public function getAllAssets(Request $request): LengthAwarePaginator
    {
        $query = Asset::select([
                'id', 'asset_name', 'asset_code', 'category_id', 
                'department_id', 'brand', 'purchase_date', 
                'total_quantity', 'remaining_quantity', 'status', 'created_at'
            ])
            ->with([
                'category:id,name', 
                'department:id,name'
            ]);

        // Filtering
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $query->latest();

        $perPage = $request->integer('per_page', 15);
        $perPage = min($perPage, 100);

        return $query->paginate($perPage);
    }

    /**
     * Create a new asset.
     */
    public function createAsset(array $data): Asset
    {
        return DB::transaction(function () use ($data) {
            // Business Rule: remaining_quantity equals total_quantity on creation
            $data['remaining_quantity'] = $data['total_quantity'];
            $data['status'] = $data['status'] ?? 'available';

            return Asset::create($data);
        });
    }

    /**
     * Update an existing asset.
     */
    public function updateAsset(Asset $asset, array $data): Asset
    {
        return DB::transaction(function () use ($asset, $data) {
            $newTotalQty = $data['total_quantity'] ?? $asset->total_quantity;

            // Auto-adjust remaining_quantity when total_quantity changes
            // and remaining_quantity is not explicitly provided.
            if (isset($data['total_quantity']) && !isset($data['remaining_quantity'])) {
                $delta = $newTotalQty - $asset->total_quantity;
                $newRemainingQty = $asset->remaining_quantity + $delta;

                // Ensure it stays within bounds [0, newTotal]
                $newRemainingQty = max(0, min($newRemainingQty, $newTotalQty));
                $asset->remaining_quantity = $newRemainingQty;
            }

            $asset->update($data);
            return $asset;
        });
    }

    /**
     * Delete an asset.
     */
    public function deleteAsset(Asset $asset): bool
    {
        return $asset->delete();
    }
}
