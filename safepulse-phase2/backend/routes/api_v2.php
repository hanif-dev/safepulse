<?php

use App\Http\Controllers\Api\AdaptiveResponseController;
use App\Http\Controllers\Api\MigrantEducationController;
use App\Http\Controllers\Api\RecoveryController;
use App\Http\Controllers\Api\WorkshopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Phase 2 API Routes (v2)
|--------------------------------------------------------------------------
| Include this file from routes/api.php with:
|   Route::prefix('v2')->group(base_path('routes/api_v2.php'));
*/

// ── Recovery Pathway ──────────────────────────────────────────────────────
Route::prefix('recovery-pathways')->group(function () {
    Route::get('/',                       [RecoveryController::class, 'index']);
    Route::get('/{slug}',                 [RecoveryController::class, 'show']);
    Route::get('/{slug}/templates/{kind}',[RecoveryController::class, 'template']);
});

Route::get('/legal-aid', [RecoveryController::class, 'legalAid']);

// ── Adaptive Response (Quick + Deep Mode) ────────────────────────────────
Route::prefix('adaptive')->group(function () {
    Route::post('/quick',          [AdaptiveResponseController::class, 'quick']);
    Route::post('/deep/start',     [AdaptiveResponseController::class, 'deepStart']);
    Route::post('/deep/answer',    [AdaptiveResponseController::class, 'deepAnswer']);
    Route::post('/deep/resolve',   [AdaptiveResponseController::class, 'deepResolve']);
});

// ── Migrant Worker Education ─────────────────────────────────────────────
Route::prefix('migrant')->group(function () {
    Route::get('/curriculum',           [MigrantEducationController::class, 'curriculum']);
    Route::get('/modules/{id}',         [MigrantEducationController::class, 'module']);
    Route::post('/assessments/pre',     [MigrantEducationController::class, 'preAssessment']);
    Route::post('/assessments/post',    [MigrantEducationController::class, 'postAssessment']);
});

// ── Workshop Integration ──────────────────────────────────────────────────
Route::prefix('workshop')->group(function () {
    // Facilitator (admin-token)
    Route::post('/sessions',                           [WorkshopController::class, 'createSession']);

    // Participant (anonymous)
    Route::post('/sessions/{code}/join',               [WorkshopController::class, 'joinSession']);
    Route::post('/assessments',                        [WorkshopController::class, 'submitAssessment']);
    Route::post('/certificates/{participantCode}',     [WorkshopController::class, 'issueCertificate']);

    // Public verification
    Route::get('/certificates/verify/{hash}',          [WorkshopController::class, 'verifyCertificate']);
});
