<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SessionToken extends Model
{
    
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'token',
        'type',
        'user_id',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Find a valid token by type
     */
    public static function findValidToken(?string $token, string $type): ?self
    {
        if (!$token) {
            return null;
        }

        return self::where('token', $token)
            ->where('type', $type)
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Generate a new token for user
     */
    public static function generate(string $type, User $user): string
    {
        // Token generation based on type
        if ($type === 'signup_token') {
            // Long token returned to client during signup (24 hour validity)
            $token = str()->random(60);
            $expiresAt = now()->addHours(24);
        } elseif ($type === 'verification_token' || $type === 'signup_verification_token' || $type === 'forgot_password_token') {
            // 6-digit OTP for verification and password reset (10 minute validity)
            $token = (string) random_int(100000, 999999);
            $expiresAt = now()->addMinutes(10);
        } else {
            // Default: 60-char token for session access (30 day validity)
            $token = str()->random(60);
            $expiresAt = now()->addDays(30);
        }
        
        self::create([
            'token'      => $token,
            'type'       => $type,
            'user_id'    => $user->id,
            'expires_at' => $expiresAt,
        ]);

        return $token;
    }
}
