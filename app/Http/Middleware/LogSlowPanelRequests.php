<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogSlowPanelRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = hrtime(true);

        $response = $next($request);

        if (! $request->is('yonetim*') && ! $request->is('livewire/*')) {
            return $response;
        }

        $durationMs = (hrtime(true) - $start) / 1_000_000;
        $thresholdMs = max(250, (int) env('SLOW_REQUEST_THRESHOLD_MS', 2000));

        if ($durationMs >= $thresholdMs) {
            Log::warning('Slow panel request detected', [
                'method' => $request->method(),
                'path' => $request->path(),
                'full_url' => $request->fullUrl(),
                'route' => optional($request->route())->getName(),
                'status' => $response->getStatusCode(),
                'duration_ms' => round($durationMs, 2),
                'user_id' => optional($request->user())->id,
                'tenant_id' => function_exists('tenant') && tenant() ? tenant('id') : null,
            ]);
        }

        return $response;
    }
}
