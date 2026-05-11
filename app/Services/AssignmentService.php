<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetAssignment;
use Illuminate\Support\Facades\DB;
// Exception import karne ki zaroorat khatam ho gayi kyunki ab error middleware throw kar raha hai

class AssignmentService
{
    public function checkout(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Asset nikalain aur lock lagayen taake koi aur same time pe update na kare
            $asset = Asset::where('id', $data['asset_id'])->lockForUpdate()->first();

            // (Stock check yahan se hata diya gaya hai kyunki wo 'check.stock' middleware mein hai)

            // 2. Asset ki quantity update karein
            $asset->quantity -= $data['quantity'] ?? 1; // Agar frontend se quantity na aaye toh 1 minus kare
            if ($asset->quantity == 0) {
                $asset->status = 'Deployed';
            }
            $asset->save();

            // 3. Naya Assignment record create karein (Naye table design ke mutabiq)
            $assignment = AssetAssignment::create([
                'asset_id'    => $data['asset_id'],
                'employee_id' => $data['employee_id'],
                'assigned_by' => auth()->id() ?? 1, // Jis admin ne login kiya hai uski ID (filhal 1 agar auth nahi hai)
                'quantity'    => $data['quantity'] ?? 1,
                'assign_date' => now(), // Naye column ka naam
                'status'      => 'Assigned' // Naye column ke hisaab se
            ]);

            return $assignment;
        });
    }

    public function checkin(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Active assignment nikalain
            $assignment = AssetAssignment::where('asset_id', $data['asset_id'])
                ->whereNull('return_date') // Naye column ka naam 'return_date'
                ->first();

            // (Active assignment check yahan se hata diya kyunki 'verify.active' middleware yeh kar raha hai)

            // 2. Assignment Update Karein (Asset wapis aagaya)
            $assignment->return_date = now();
            $assignment->status = 'Returned'; // Status update kar diya
            $assignment->save();

            // 3. Asset ki Quantity wapis plus karein aur Status 'Available' karein
            $asset = Asset::where('id', $data['asset_id'])->lockForUpdate()->first();
            $asset->quantity += $assignment->quantity; // Jitni quantity di thi, utni wapis plus ki
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

        // assigner relation add ki hai taake jisne assign kiya uski detail b aaye
        $query = AssetAssignment::with(['employee', 'asset', 'assigner']); 

        if ($assetId) {
            $query->where('asset_id', $assetId);
        }

        // 'assign_date' naye column name ke hisaab se sort karega
        return $query->orderBy('assign_date', 'desc')->get();
    }
}