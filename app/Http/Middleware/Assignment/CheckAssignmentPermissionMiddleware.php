<?php
namespace App\Http\Middleware\Assignment;
use Closure;
use Illuminate\Http\Request;

class CheckAssignmentPermissionMiddleware {
    public function handle(Request $request, Closure $next) {

        $user = $request->user(); 
        
        if (!$user || $user->role !== 'Admin') { 
            return response()->forbidden("Sirf Admin hi assets handle kar sakta hai.");
        }
        return $next($request);
    }
}