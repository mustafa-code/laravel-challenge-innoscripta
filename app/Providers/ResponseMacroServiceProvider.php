<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($message = "Request completed successfully", $key = "completed.successful", $status = 200, $data = []) {
            $result = [
                "message" => $message,
                "success" => true,
                "key" => $key,
            ];
            if (!empty($data)) {
                $result['data'] = $data;
            }
            return Response::json($result, $status);
        });

        Response::macro('error', function ($message = "Internal server error", $key = "internal.error", $status = 500, $data = []) {
            $result = [
                "message" => $message,
                "success" => false,
                "key" => $key,
            ];
            if (!empty($data)) {
                $result['data'] = $data;
            }
            return Response::json($result, $status);
        });
    }
}
