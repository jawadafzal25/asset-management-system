<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WebhookNotifierService
{
    private const WEBHOOK_URL = 'https://app.whistleit.io/api/webhooks/69cfc4e010aee883cc006ef0';

    public static function notifyIfServerError(Throwable $exception): void
    {
        $request = app('request');

        \Illuminate\Support\Facades\Log::info('Webhook notifier called for ' . get_class($exception));

        $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;

        if ($status < 500 || $status >= 600) {
            return;
        }

        try {
            $response = Http::timeout(3)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post(self::WEBHOOK_URL, [
                    'type' => 'server_error',
                    'status' => $status,
                    'message' => $exception->getMessage(),
                    'exception' => get_class($exception),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                    'timestamp' => now()->toISOString(),
                ]);

            if ($response->failed()) {
                Log::error('Webhook notifier failed', [
                    'webhook_url' => self::WEBHOOK_URL,
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                ]);
            }
        } catch (Throwable $reportException) {
            Log::error('Webhook notifier exception', [
                'error' => $reportException->getMessage(),
                'webhook_url' => self::WEBHOOK_URL,
                'original_exception' => get_class($exception),
                'original_message' => $exception->getMessage(),
            ]);
        }
    }
}
