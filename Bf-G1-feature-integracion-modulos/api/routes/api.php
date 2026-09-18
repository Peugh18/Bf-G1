<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HealthCheckController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PreferencesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make system magic!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/health', HealthCheckController::class);
    Route::post('/auth/register', [AuthController::class, 'register'])->middleware('throttle:auth');
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:auth');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::get('/auth/preferences', [PreferencesController::class, 'show']);
        Route::put('/auth/preferences', [PreferencesController::class, 'update']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/change-password', [AuthController::class, 'changePassword'])->middleware('throttle:auth');
        Route::get('/customers/export/pdf', [ReportController::class, 'exportCustomersPdf']);
        Route::get('/catalog/export/pdf', [ReportController::class, 'exportCatalogPdf']);
        Route::get('/customers/export/excel', [ReportController::class, 'exportCustomersExcel']);
        Route::get('/catalog/export/excel', [ReportController::class, 'exportCatalogExcel']);
    });
});
