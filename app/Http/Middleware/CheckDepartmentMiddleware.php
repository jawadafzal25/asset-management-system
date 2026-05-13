<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Department;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDepartmentMiddleware
{
   
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get the ID from the route parameter /{id}
        $id = $request->route('id');

        // 2. If an ID is provided, check if it exists in the database
        if ($id) {
            $department = Department::find($id);

            // 3. If not found, use your global 'notFound' response
            if (!$department) {
                return response()->notFound("Department with ID $id not found.");
            }

            $request->attributes->add(['department_data' => $department]);
        }

        return $next($request);
    }
}
