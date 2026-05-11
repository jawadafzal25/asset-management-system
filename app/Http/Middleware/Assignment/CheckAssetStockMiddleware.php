<?php
namespace App\Http\Middleware\Assignment;
use Closure;
use App\Models\Asset;

class CheckAssetStockMiddleware {
    public function handle($request, Closure $next) {
        $asset = Asset::find($request->asset_id);
        if ($asset && ($asset->quantity <= 0 || $asset->status !== 'Available')) {
            return response()->error("Asset ka stock khatam hai ya available nahi hai.", 400);
        }
        return $next($request);
    }
}