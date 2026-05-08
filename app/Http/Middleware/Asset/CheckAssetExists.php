<?php

namespace App\Http\Middleware\Asset;

use Closure;
use Illuminate\Http\Request;
use App\Models\Asset;
use Symfony\Component\HttpFoundation\Response;


class CheckAssetExists
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');

        $asset = Asset::with(['category', 'department'])->find($id);

        if (!$asset) {
            return response()->notFound('Asset not found.');
        }

        // Bind resolved asset to request attributes for reuse in subsequent
        // middleware (ValidateUpdateAsset) and in the controller.
        $request->attributes->set('asset', $asset);

        return $next($request);
    }
}
