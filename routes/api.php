<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CugAdmissionsController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// CUG Admissions phone lookup (no auth required for registration)
Route::post('/cug-admissions/lookup', [CugAdmissionsController::class, 'lookup'])
    ->middleware('throttle:10,1'); // Rate limit: 10 requests per minute

// Priority Bank Central Finance API Webhooks
// These endpoints receive finance data from Priority Bank when CEO creates
// income/expense entries and selects Priority Accommodation system.
use App\Http\Controllers\PriorityBankWebhookController;
Route::post('/webhook/finance/income', [PriorityBankWebhookController::class, 'handleIncome'])
    ->name('api.webhook.priority-bank.income');

Route::post('/webhook/finance/expense', [PriorityBankWebhookController::class, 'handleExpense'])
    ->name('api.webhook.priority-bank.expense');