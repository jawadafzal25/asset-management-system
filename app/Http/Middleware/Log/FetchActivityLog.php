<?php

namespace App\Http\Middleware\Log;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * FetchActivityLog
 *
 * Same pattern as CheckEmployeeMiddleware, CheckAssetExists etc.
 * Reads {id} from route, fetches the log, sets on request attributes.
 * Returns 404 if not found — controller never runs.
 */
class FetchActivityLog
{
    public function handle(Request $request, Closure $next): Response
    {
        $id  = $request->route('id');
        $log = ActivityLog::find($id);

        if (!$log) {
            return response()->notFound('Activity log entry not found.');
        }

        $request->attributes->set('log_data', $log);

        return $next($request);
    }
}
