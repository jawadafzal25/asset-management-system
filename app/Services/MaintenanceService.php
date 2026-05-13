<?php

namespace App\Services;

use App\Models\Maintenance;
use App\Repositories\MaintenanceRepository;

class MaintenanceService
{
    public function __construct(
        protected MaintenanceRepository $repository
    ) {}

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function readAll($search = null)
    {
        return $this->repository->readAll($search);
    }

    public function read($id)
    {
        return $this->repository->read($id);
    }

    public function update($id, array $data)
    {
        $maintenance = $this->repository->read($id);

        return $this->repository->update(
            $maintenance,
            $data
        );
    }

    public function delete($id)
    {
        $maintenance = $this->repository->read($id);

        return $this->repository->delete(
            $maintenance
        );
    }
}