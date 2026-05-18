<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetAssignment;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $asset = Asset::where('id', $data['asset_id'])->lockForUpdate()->first();

            $qtyToAssign = $data['quantity'] ?? 1;

            if ($asset->remaining_quantity < $qtyToAssign) {
                throw new \Exception('Insufficient remaining quantity of this asset to assign.');
            }

            $asset->remaining_quantity -= $qtyToAssign; 
            if ($asset->remaining_quantity == 0) {
                $asset->status = 'assigned';
            }
            $asset->save();

            $assignment = AssetAssignment::create([
                'asset_id'    => $data['asset_id'],
                'employee_id' => $data['employee_id'],
                'assigned_by' => auth()->id() ?? 1, 
                'quantity'    => $data['quantity'] ?? 1,
                'assign_date' => now(),
                'status'      => 'Assigned',
                'description' => $data['description'] ?? null,
            ]);

            return $assignment;
        });
    }

    public function returnAsset(array $data)
    {
        return DB::transaction(function () use ($data) {
           
            $assignment = AssetAssignment::where('asset_id', $data['asset_id'])
                ->whereNull('return_date') 
                ->first();

            if (!$assignment) {
                throw new \Exception('No active assignment found for this asset');
            }
            
            $assignment->return_date = now();
            $assignment->status = 'Returned'; 
            $assignment->save();

            $asset = Asset::where('id', $data['asset_id'])->lockForUpdate()->first();
            $asset->remaining_quantity += $assignment->quantity;
            $asset->status = 'available';
            $asset->save();

            return $assignment;
        });
    }

    public function getHistory()
    {
        return AssetAssignment::with(['employee', 'asset', 'assigner'])
            ->orderBy('assign_date', 'desc')
            ->get();
    }
}