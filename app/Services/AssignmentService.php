<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetAssignment;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    public function checkout(array $data)
    {
        return DB::transaction(function () use ($data) {
           $asset = Asset::where('id', $data['asset_id'])->lockForUpdate()->first();

           
            $asset->quantity -= $data['quantity'] ?? 1; 
            if ($asset->quantity == 0) {
                $asset->status = 'Deployed';
            }
            $asset->save();

            $assignment = AssetAssignment::create([
                'asset_id'    => $data['asset_id'],
                'employee_id' => $data['employee_id'],
                'assigned_by' => auth()->id() ?? 1, 
                'quantity'    => $data['quantity'] ?? 1,
                'assign_date' => now(),
                'status'      => 'Assigned'
            ]);

            return $assignment;
        });
    }

    public function checkin(array $data)
    {
        return DB::transaction(function () use ($data) {
           
            $assignment = AssetAssignment::where('asset_id', $data['asset_id'])
                ->whereNull('return_date') 
                ->first();

           
            
            $assignment->return_date = now();
            $assignment->status = 'Returned'; 
            $assignment->save();

            $asset = Asset::where('id', $data['asset_id'])->lockForUpdate()->first();
            $asset->quantity += $assignment->quantity;
            $asset->status = 'Available';
            $asset->save();

            return $assignment;
        });
    }

    public function getHistory($assetId = null)
    {
        if ($assetId) {
            \App\Models\Asset::findOrFail($assetId); 
        }

        $query = AssetAssignment::with(['employee', 'asset', 'assigner']); 

        if ($assetId) {
            $query->where('asset_id', $assetId);
        }

        return $query->orderBy('assign_date', 'desc')->get();
    }
}