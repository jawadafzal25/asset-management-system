<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityLogResource;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * GET /api/logs
     *
     * Returns paginated activity logs.
     *
     * Filters (all optional query params):
     *   ?module=Employee
     *   ?action=deleted
     *   ?status=failed
     *   ?user_id=5
     *   ?from=2026-01-01
     *   ?to=2026-12-31
     *   ?per_page=20
     */
    public function read(Request $request): JsonResponse
    {
        $query = ActivityLog::query()->latest();

        // Filter by module
        if ($request->filled('module')) {
            $query->where('module', $request->input('module'));
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }

        $perPage = min($request->integer('per_page', 20), 100);
        $logs    = $query->paginate($perPage);

        return response()->success(
            ActivityLogResource::collection($logs),
            'Activity logs retrieved successfully.'
        );
    }

    /**
     * GET /api/logs/{id}
     *
     * Single log detail.
     * FetchActivityLog middleware sets 'log_data' on request attributes.
     */
    public function detail(Request $request): JsonResponse
    {
        $log = data_get($request->attributes->all(), 'log_data');

        return response()->success(
            new ActivityLogResource($log),
            'Log entry retrieved successfully.'
        );
    }
}
