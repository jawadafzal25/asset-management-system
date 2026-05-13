<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use MongoDB\Client;
use MongoDB\GridFS\Bucket;
use MongoDB\BSON\ObjectId;

/**
 * AssetService
 *
 * Encapsulates all business logic for the Asset module.
 * Now integrated with MongoDB GridFS for file storage.
 */
class AssetService
{
    protected ?Bucket $bucket = null;

    /**
     * Initialize the GridFS bucket.
     */
    protected function getBucket(): Bucket
    {
        if ($this->bucket === null) {
            $host = env('MONGODB_HOST', '127.0.0.1');
            $port = env('MONGODB_PORT', 27017);
            $dbName = env('MONGODB_DATABASE', 'asset_management_system');
            
            $uri = "mongodb://{$host}:{$port}";
            
            $client = new Client($uri);
            $database = $client->selectDatabase($dbName);
            $this->bucket = $database->selectGridFSBucket();
        }
        return $this->bucket;
    }

    /**
     * Get a paginated and filtered list of assets.
     */
    public function getAllAssets(Request $request): LengthAwarePaginator
    {
        $query = Asset::select([
                'id', 'asset_name', 'asset_code', 'category_id', 
                'department_id', 'brand', 'purchase_date', 
                'total_quantity', 'remaining_quantity', 'status', 'created_at',
                'asset_image', 'invoice_image'
            ])
            ->with([
                'category:id,name', 
                'department:department_id,department_name'
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
            // Handle GridFS uploads
            if (isset($data['asset_image']) && $data['asset_image'] instanceof UploadedFile) {
                $data['asset_image'] = $this->uploadToGridFS($data['asset_image']);
            } else {
                $data['asset_image'] = null;
            }

            if (isset($data['invoice_image']) && $data['invoice_image'] instanceof UploadedFile) {
                $data['invoice_image'] = $this->uploadToGridFS($data['invoice_image']);
            } else {
                $data['invoice_image'] = null;
            }

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
            // Handle GridFS uploads
            if (isset($data['asset_image']) && $data['asset_image'] instanceof UploadedFile) {
                $this->deleteFromGridFS($asset->asset_image);
                $data['asset_image'] = $this->uploadToGridFS($data['asset_image']);
            }

            if (isset($data['invoice_image']) && $data['invoice_image'] instanceof UploadedFile) {
                $this->deleteFromGridFS($asset->invoice_image);
                $data['invoice_image'] = $this->uploadToGridFS($data['invoice_image']);
            }

            $newTotalQty = $data['total_quantity'] ?? $asset->total_quantity;

            if (isset($data['total_quantity']) && !isset($data['remaining_quantity'])) {
                $delta = $newTotalQty - $asset->total_quantity;
                $newRemainingQty = $asset->remaining_quantity + $delta;
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
        $this->deleteFromGridFS($asset->asset_image);
        $this->deleteFromGridFS($asset->invoice_image);
        
        return $asset->delete();
    }

    /**
     * Internal helper to upload a file to MongoDB GridFS.
     */
    private function uploadToGridFS(UploadedFile $file): string
    {
        try {
            $bucket = $this->getBucket();
            $stream = fopen($file->getRealPath(), 'rb');
            
            $fileId = $bucket->uploadFromStream(
                $file->getClientOriginalName(),
                $stream,
                [
                    'metadata' => [
                        'mimeType' => $file->getMimeType(),
                    ],
                ]
            );

            fclose($stream);
            return (string) $fileId;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception("MongoDB Upload Failed: " . $e->getMessage());
        }
    }

    /**
     * Internal helper to delete a file from GridFS.
     */
    private function deleteFromGridFS(?string $id): void
    {
        if (!$id || strlen($id) !== 24) return;

        try {
            $bucket = $this->getBucket();
            $bucket->delete(new ObjectId($id));
        } catch (\Exception $e) {
            // Silently fail if file doesn't exist
        }
    }
}
