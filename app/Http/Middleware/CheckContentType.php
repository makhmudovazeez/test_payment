<?php

namespace App\Http\Middleware;

use App\Service\MerchantService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckContentType
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $contentType = $request->header('Content-Type');
        if ($contentType === MerchantService::APPLICATION_JSON || str_contains($contentType, MerchantService::FORM_DATA)) {
            if (str_contains($contentType,MerchantService::FORM_DATA)) {
                // if developer will test with postman
                // we should replace Content-Type just in case
                $request->headers->set('Content-Type', MerchantService::FORM_DATA);
            }
            return $next($request);
        }

        return \response('', Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }
}
