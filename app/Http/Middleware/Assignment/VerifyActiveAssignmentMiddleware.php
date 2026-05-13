<?php
namespace App\Http\Middleware\Assignment;
use Closure;
use App\Models\AssetAssignment;

class VerifyActiveAssignmentMiddleware {
    public function handle($request, Closure $next) {
        $active = AssetAssignment::where('asset_id', $request->asset_id)
                                 ->whereNull('return_date')->first();
        if (!$active) {
            return response()->error("Is asset ki koi active assignment nahi mili.", 404);
        }
        return $next($request);
    }
}