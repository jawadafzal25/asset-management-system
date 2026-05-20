<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\Auth\UpdateRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\Auth\VerifySignupRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\OrganizationResource;
use App\Models\SessionToken;
use App\Models\User;
use App\Models\Organization;
use App\Notifications\ForgetPasswordNotification;
use App\Notifications\SignupVerificationNotification;

class AuthController extends Controller
{
    /**
     * POST /api/auth/signup
     * Register a new user and send email verification.
     * 
     * Returns both a signup_token (for client-side verification) and 
     * sends a verification_token (6-digit OTP) via email.
     */
    public function signup(SignupRequest $request)
    {
        $data = $request->validated();

        // Create organization first with just name
        $organizationData = ['name' => $data['organization']];
        $organization = Organization::createOrganization($organizationData, null);

        // Create user with organization relationship
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'organization_id' => $organization->id,
        ];

        $user = User::add($userData);

        // Update organization created_by field with actual user ID
        $organization->update(['created_by' => $user->id]);

        // Generate signup token (long token returned to client, valid for 24 hours)
        $signupToken = SessionToken::generate('signup_token', $user);

        // Generate verification token (6-digit OTP sent via email, valid for 10 minutes)
        $verificationToken = SessionToken::generate('verification_token', $user);

        $user->notify(new SignupVerificationNotification($user, $verificationToken));

        return response()->success([
            'user' => UserResource::make($user),
            'organization' => OrganizationResource::make($organization),
            'signup_token' => $signupToken,
        ], 'Signup successful! Please check your email for a verification code.');
    }

    /**
     * POST /api/auth/verify-signup
     * Verify the user's email using both signup token and verification code.
     * Requires both tokens for security.
     */
    public function verifySignup(VerifySignupRequest $request)
    {
        $user = data_get($request, 'verified_user');
        $signupTokenRecord = data_get($request, 'signup_token_record');
        $verificationTokenRecord = data_get($request, 'verification_token_record');

        // Activate user
        $user->update(['is_active' => true]);

        // Delete both tokens
        $signupTokenRecord->delete();
        $verificationTokenRecord->delete();

        return response()->success([
            'user' => UserResource::make($user),
        ], 'Account activated successfully! You can now login.');
    }

    /**
     * POST /api/auth/login
     * Authenticate user and return access token.
     */
    public function login(LoginRequest $request)
    {
        $user = data_get($request, 'user');

        // Expire/Revoke all previous active access tokens for this user
        SessionToken::where('user_id', $user->id)->where('type', 'access_token')->delete();

        $token = SessionToken::generate('access_token', $user);

        return response()->success([
            'access_token' => $token,
            'user'         => UserResource::make($user),
        ], 'Login successful!');
    }

    /**
     * POST /api/auth/logout
     * Revoke the user's access token.
     */
    public function logout(LogoutRequest $request)
    {
        $tokenRecord = data_get($request, 'token_record');

        if ($tokenRecord) {
            $tokenRecord->delete();
        }

        return response()->success(null, 'Logout successful!');
    }

    /**
     * POST /api/auth/forgot-password
     * Send a password reset link to the user's email.
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = data_get($request, 'user');

        $token = SessionToken::generate('forgot_password_token', $user);

        $user->notify(new ForgetPasswordNotification($user, $token));
        
        return response()->success(null, 'Password reset link sent to your email.');
    }

    /**
     * POST /api/auth/reset-password
     * Reset the user's password using the reset token.
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $tokenRecord = data_get($request, 'token_record');
        $user        = data_get($request, 'user');

        $user->update([
            'password' => data_get($request, 'password'),
        ]);

        $tokenRecord->delete();

        return response()->success(null, 'Password reset successfully!');
    }

    /**
     * PATCH /api/auth/update-password
     * Update user's password with authorization token validation
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = data_get($request, 'user');
        
        // Update the password
        $user->update([
            'password' => $request->validated()['new_password'],
        ]);

        return response()->success([
            'user' => UserResource::make($user),
        ], 'Password updated successfully!');
    }

    /**
     * GET /api/auth/read
     * Retrieve the authenticated user's details.
     */
    public function read(\Illuminate\Http\Request $request)
    {
        $users = User::all();

        return response()->success([
            'users' => UserResource::collection($users),
        ], 'All users retrieved successfully!');
    }

    /**
     * POST /api/auth/update
     * Update the authenticated user's profile information.
     */
    public function update(UpdateRequest $request)
    {
        $user = data_get($request, 'user');

        $user->update($request->validated());

        return response()->success([
            'user' => UserResource::make($user),
        ], 'Profile updated successfully!');
    }
}
