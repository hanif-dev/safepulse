<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\KnowledgeController;
use App\Http\Controllers\Api\ScamCheckerController;
use App\Http\Controllers\Api\StatsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — SafePulse
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/ping', fn () => response()->json(['status' => 'ok', 'app' => 'SafePulse']));

// Public — articles
Route::get('/articles',          [ArticleController::class, 'index']);
Route::get('/articles/{slug}',   [ArticleController::class, 'show']);

// Public — incidents (write-only for public; anonymous)
Route::post('/incidents',        [IncidentController::class, 'store']);

// Public — scam checker
Route::post('/check-scam',       [ScamCheckerController::class, 'check']);

// Public — aggregated dashboard data
Route::get('/stats/overview',    [StatsController::class, 'overview']);

// ── Admin (X-Admin-Token required) ──────────────────────────────────────
Route::prefix('admin/knowledge')->group(function () {
    Route::get('/status',  [KnowledgeController::class, 'status']);
    Route::get('/',        [KnowledgeController::class, 'index']);
    Route::post('/',       [KnowledgeController::class, 'store']);
    Route::put('/{id}',    [KnowledgeController::class, 'update']);
    Route::delete('/{id}', [KnowledgeController::class, 'destroy']);
});
