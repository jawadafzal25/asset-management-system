<?php
namespace App\Http\Middleware\Assignment;
use Closure;
use App\Models\Asset;
use App\Models\Employee;

class ValidateEntitiesMiddleware {
    public function handle($request, Closure $next) {
        if ($request->has('asset_id') && !Asset::find($request->asset_id)) {
            return response()->notFound("Asset ID mojud nahi hai.");
        }
        if ($request->has('employee_id') && !Employee::find($request->employee_id)) {
            return response()->notFound("Employee ID mojud nahi hai.");
        }
        return $next($request);
    }
}