<?php

namespace App\Repositories;

use App\Models\Maintenance;

class MaintenanceRepository
{
    public function create(array $data)
    {
        return Maintenance::create($data);
    }
    public function readAll($search = null)
{
    $search = trim($search);

    return Maintenance::query()
        ->with(['asset', 'user'])
        ->when($search !== null && $search !== '', function ($q) use ($search) {
            $q->where('issue_description', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10);
}

    public function read($id)
    {
        return Maintenance::findOrFail($id);
    }
    public function update(
        $maintenance,
        array $data
    ) {
        $maintenance->update($data);

        return $maintenance->fresh();
    }

    public function delete($maintenance)
    {
        return $maintenance->delete();
    }
}