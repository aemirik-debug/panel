<?php

namespace App\Http\Middleware;

use App\Models\Domain;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyForLivewire
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $centralDomains = (array) config('tenancy.central_domains', []);

        if (! in_array($host, $centralDomains, true)) {
            $tenantDomain = Domain::query()
                ->where('domain', $host)
                ->with('tenant')
                ->first();

            if ($tenantDomain?->tenant && function_exists('tenancy')) {
                tenancy()->initialize($tenantDomain->tenant);
            }
        }

        return $next($request);
    }
}
