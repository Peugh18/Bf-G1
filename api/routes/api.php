<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ReportController;

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
    Route::get('/customers/export/pdf', [ReportController::class, 'exportCustomersPdf']);
    Route::get('/catalog/export/pdf', [ReportController::class, 'exportCatalogPdf']);
});