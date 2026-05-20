<?php

namespace App\Http\Middleware\Auth;

use App\Models\SessionToken;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckVerifySignupMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $signupToken = $request->input('signup_token');
        $verificationCode = $request->input('verification_code');

        if (!$signupToken || !$verificationCode) {
            return response()->error('Both signup_token and verification_code are required.', 422);
        }

        // Find valid signup token
        $signupTokenRecord = SessionToken::findValidToken($signupToken, 'signup_token');
        if (!$signupTokenRecord) {
            return response()->error('Invalid or expired signup token.', 422);
        }

        // Find valid verification token
        $verificationTokenRecord = SessionToken::findValidToken($verificationCode, 'verification_token');
        if (!$verificationTokenRecord) {
            return response()->error('Invalid or expired verification code.', 422);
        }

        // Both tokens must belong to the same user
        if ($signupTokenRecord->user_id !== $verificationTokenRecord->user_id) {
            return response()->error('Tokens do not match the same user account.', 422);
        }

        // Find the user through the token records
        $user = User::find($signupTokenRecord->user_id);

        if (!$user) {
            return response()->error('User account not found.', 404);
        }

        if ($user->is_active) {
            return response()->error('This account is already verified.', 409);
        }

        $request->merge([
            'verified_user' => $user,
            'signup_token_record' => $signupTokenRecord,
            'verification_token_record' => $verificationTokenRecord,
        ]);

        return $next($request);
    }
}
