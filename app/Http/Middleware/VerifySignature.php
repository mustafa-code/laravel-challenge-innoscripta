<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Use local env to pypass the signature check...
        if (config('app.env') === 'local') {
            return $next($request);
        }

        $timestamp = $request->header('X-Timestamp');
        $signature = $request->header('X-Signature');

        // TODO: Enhance the secret key store and exchange with frontend side...
        $secret_key = "0195765c-e721-76a4-9aea-f4659f90aedf";

        $expectedSignature = hash('sha256', $secret_key . $timestamp);
        if ($signature !== $expectedSignature) {
            return response()->error(
                message: __('Invalid signature'),
                key: 'invalid.signature',
                status: 401,
            );
        }
        if (abs(time() - (int)$timestamp) > 300) {
            return response()->error(
                message: __('Timestamp expired'),
                key: 'timestamp.expired',
                status: 401,
            );
        }

        return $next($request);
    }
}
