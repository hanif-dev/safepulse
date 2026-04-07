<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ScamCheckerController;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\StatsController;

/*
|--------------------------------------------------------------------------
| SafePulse API Routes  (no authentication)
|--------------------------------------------------------------------------
*/

// ── Scam / Risk Checker ───────────────────────────────────────────────────────
Route::post('/check-scam', [ScamCheckerController::class, 'check']);

// ── Incident Reporter (anonymous) ─────────────────────────────────────────────
Route::post('/incidents', [IncidentController::class, 'store']);

// ── Insights / Threat Library ─────────────────────────────────────────────────
Route::get('/articles',         [ArticleController::class, 'index']);
Route::get('/articles/{slug}',  [ArticleController::class, 'show']);

// ── Public Health Impact Stats ────────────────────────────────────────────────
Route::get('/stats/overview',   [StatsController::class, 'overview']);

// ── Health-check ─────────────────────────────────────────────────────────────
Route::get('/ping', fn () => response()->json(['status' => 'ok', 'app' => 'SafePulse']));
