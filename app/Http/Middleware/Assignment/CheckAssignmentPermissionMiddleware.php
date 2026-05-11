<?php
namespace App\Http\Middleware\Assignment;
use Closure;
use Illuminate\Http\Request;

class CheckAssignmentPermissionMiddleware {
    public function handle(Request $request, Closure $next) {
        // Assume kar rahe hain Ahmed Raza ke token se user mil raha hai
        $user = $request->user(); 
        
        if (!$user || $user->role !== 'Admin') { // Role check logic
            return response()->forbidden("Sirf Admin hi assets handle kar sakta hai.");
        }
        return $next($request);
    }
}