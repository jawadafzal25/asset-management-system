<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileUpdateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = data_get($request, 'user');

        if ($request->hasFile('profile_picture')) {
            // Validate the file here since we'll replace it before FormRequest validation
            $request->validate([
                'profile_picture' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            // Delete old profile picture if exists
            if ($user && $user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            
            // Merge the stored path back into the request, replacing the file
            $request->merge(['profile_picture' => $path]);
        }

        return $next($request);
    }
}
