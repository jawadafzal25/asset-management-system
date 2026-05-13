<?php

namespace App\Http\Middleware\Employee;

use Closure;
use App\Models\Employee;
use Illuminate\Http\Request;

class CheckEmployeeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route('id');

        if ($id) {
            // Eager load 'department' so it's ready for the response
            $employee = Employee::with('department')->find($id);

            if (!$employee) {
                return response()->notFound("Employee with ID $id not found.");
            }

            // Attach the found employee to the request attributes
            $request->attributes->add(['employee_data' => $employee]);
        }

        return $next($request);
    }
}
