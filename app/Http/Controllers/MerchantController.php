<?php

namespace App\Http\Controllers;

use App\Exceptions\MediaTypeException;
use App\Http\Requests\MerchantRequest;
use App\Service\MerchantService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class MerchantController extends Controller
{
    public function __construct(protected MerchantService $merchantService)
    {
    }

    public function merchant(MerchantRequest $request)
    {
        try {
            $resp = $this->merchantService->
            setContentType($request->header('Content-Type'))->
            handle($request->validated());

            return response()->json([
                'result' => $resp
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
}
