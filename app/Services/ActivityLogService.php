<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogService
{
    /**
     * The central method that writes every log entry.
     *
     * @param string      $action      login | logout | created | updated | deleted | failed | error
     * @param string      $module      Auth | Employee | Asset | Department | Category | Permission | Role
     * @param array       $options     optional: entity_type, entity_id, old_values, new_values,
     *                                           status, error_message, user override
     */
    public static function log(string $action, string $module, array $options = []): ActivityLog
    {
        $request = app(Request::class);

        // Resolve the user — from request merge (our custom token system) or fallback
        $user = data_get($request, 'user')
            ?? data_get($options, 'user')
            ?? null;

        return ActivityLog::create([
            'user_id'       => data_get($user, 'id'),
            'user_name'     => data_get($user, 'name'),
            'user_email'    => data_get($user, 'email'),
            'action'        => $action,
            'module'        => $module,
            'entity_type'   => data_get($options, 'entity_type'),
            'entity_id'     => data_get($options, 'entity_id'),
            'old_values'    => data_get($options, 'old_values'),
            'new_values'    => data_get($options, 'new_values'),
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->userAgent(),
            'url'           => $request->fullUrl(),
            'method'        => $request->method(),
            'status'        => data_get($options, 'status', 'success'),
            'error_message' => data_get($options, 'error_message'),
        ]);
    }

    /**
     * Log a model creation.
     * Automatically captures all the model's attributes as new_values.
     */
    public static function logCreated(string $module, Model $model): ActivityLog
    {
        return self::log('created', $module, [
            'entity_type' => class_basename($model),
            'entity_id'   => $model->getKey(),
            'new_values'  => self::sanitize($model->toArray()),
        ]);
    }

    /**
     * Log a model update.
     * Pass $oldValues BEFORE calling $model->update() to capture the diff.
     *
     * Usage:
     *   $old = $employee->toArray();
     *   $employee->update($data);
     *   ActivityLogService::logUpdated('Employee', $employee, $old);
     */
    public static function logUpdated(string $module, Model $model, array $oldValues): ActivityLog
    {
        return self::log('updated', $module, [
            'entity_type' => class_basename($model),
            'entity_id'   => $model->getKey(),
            'old_values'  => self::sanitize($oldValues),
            'new_values'  => self::sanitize($model->toArray()),
        ]);
    }

    /**
     * Log a model deletion.
     * Captures the full model data as old_values before it is gone.
     */
    public static function logDeleted(string $module, Model $model): ActivityLog
    {
        return self::log('deleted', $module, [
            'entity_type' => class_basename($model),
            'entity_id'   => $model->getKey(),
            'old_values'  => self::sanitize($model->toArray()),
        ]);
    }

    /**
     * Log a login event.
     */
    public static function logLogin(Model $user): ActivityLog
    {
        return self::log('login', 'Auth', [
            'entity_type' => 'User',
            'entity_id'   => data_get($user, 'id'),
            'new_values'  => [
                'email' => data_get($user, 'email'),
                'name'  => data_get($user, 'name'),
            ],
            'user' => $user,
        ]);
    }

    /**
     * Log a logout event.
     */
    public static function logLogout(Model $user): ActivityLog
    {
        return self::log('logout', 'Auth', [
            'entity_type' => 'User',
            'entity_id'   => data_get($user, 'id'),
            'user'        => $user,
        ]);
    }

    /**
     * Log a failed action (wrong credentials, unauthorized attempt, etc.)
     */
    public static function logFailed(string $module, string $errorMessage, array $context = []): ActivityLog
    {
        return self::log('failed', $module, array_merge($context, [
            'status'        => 'failed',
            'error_message' => $errorMessage,
        ]));
    }

    /**
     * Strip sensitive fields from data before storing in logs.
     * Passwords, tokens — never stored in plain text.
     */
    private static function sanitize(array $data): array
    {
        $sensitiveFields = [
            'password',
            'token',
            'access_token',
            'remember_token',
            'verification_code',
            'secret',
            'asset_image',   // GridFS IDs — not useful in logs
            'invoice_image',
        ];

        foreach ($sensitiveFields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = '[HIDDEN]';
            }
        }

        return $data;
    }
}
