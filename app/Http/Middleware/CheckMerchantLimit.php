<?php

namespace App\Http\Middleware;

use App\Service\MerchantService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckMerchantLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $contentType = $request->header('Content-Type');
        $limitExists = match (true) {
            $contentType === MerchantService::APPLICATION_JSON => Cache::has(MerchantService::APPLICATION_JSON),
            $contentType === MerchantService::FORM_DATA => Cache::has(MerchantService::FORM_DATA),
            default => false
        };

        if ($limitExists) {
            return \response()->json([
                'message' => 'Try later! Maybe tomorrow :)'
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        return $next($request);
    }
}
