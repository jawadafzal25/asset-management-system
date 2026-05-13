<?php

namespace App\Http\Controllers;

use App\Http\Requests\Asset\CreateAssetRequest;
use App\Http\Requests\Asset\UpdateAssetRequest;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use App\Services\AssetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * AssetController
 *
 * Cleaned up after successful GridFS integration.
 */
class AssetController extends Controller
{
    protected AssetService $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    /**
     * Read All Assets
     */
    public function readAll(Request $request): JsonResponse
    {
        $assets = $this->assetService->getAllAssets($request);

        return response()->success(
            AssetResource::collectionWithMeta($assets),
            'Assets retrieved successfully.'
        );
    }

    /**
     * Create Asset
     */
    public function create(CreateAssetRequest $request): JsonResponse
    {
        $data = array_merge($request->validated(), [
            'asset_image' => $request->file('asset_image'),
            'invoice_image' => $request->file('invoice_image'),
        ]);

        $asset = $this->assetService->createAsset($data);

        $asset->load(['category', 'department']);

        return response()->success(
            new AssetResource($asset),
            'Asset created successfully.',
            201
        );
    }

    /**
     * Read Single Asset
     */
    public function read(Request $request): JsonResponse
    {
        $asset = data_get($request->attributes->all(), 'asset');

        return response()->success(
            new AssetResource($asset),
            'Asset retrieved successfully.'
        );
    }

    /**
     * Update Asset
     */
    public function update(UpdateAssetRequest $request): JsonResponse
    {
        /** @var Asset $asset */
        $asset = data_get($request->attributes->all(), 'asset');

        $data = array_merge($request->validated(), [
            'asset_image' => $request->file('asset_image'),
            'invoice_image' => $request->file('invoice_image'),
        ]);

        $updatedAsset = $this->assetService->updateAsset($asset, $data);

        $updatedAsset->load(['category', 'department']);

        return response()->success(
            new AssetResource($updatedAsset),
            'Asset updated successfully.'
        );
    }

    /**
     * Delete Asset
     */
    public function delete(Request $request): JsonResponse
    {
        /** @var Asset $asset */
        $asset = data_get($request->attributes->all(), 'asset');

        $this->assetService->deleteAsset($asset);

        return response()->success(null, 'Asset deleted successfully.');
    }
}
