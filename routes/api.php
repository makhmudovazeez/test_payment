<?php

use App\Http\Controllers\MerchantController;
use App\Http\Middleware\CheckContentType;
use App\Http\Middleware\CheckMerchantLimit;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware([CheckContentType::class, CheckMerchantLimit::class])->group(function (Router $router) {
    $router->post('merchant', [MerchantController::class, 'merchant'])->name('api.merchant');
});
