<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * GET /api/stats/overview
     */
    public function overview(): JsonResponse
    {
        // ── By category ──────────────────────────────────────────────────────
        $byCategory = Incident::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        // ── By country ───────────────────────────────────────────────────────
        $byCountry = Incident::select('country', DB::raw('count(*) as total'))
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(30)
            ->pluck('total', 'country');

        // ── Last 12 months ───────────────────────────────────────────────────
        $last12Months = Incident::select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        // ── Summary counts ───────────────────────────────────────────────────
        $totalIncidents       = Incident::count();
        $highImpactIncidents  = Incident::where('health_impact_level', 'high')->count();
        $totalFinancialLoss   = Incident::sum('financial_loss_estimate');
        $countriesAffected    = Incident::distinct()->count('country');

        return response()->json([
            'summary' => [
                'total_incidents'      => $totalIncidents,
                'high_impact'          => $highImpactIncidents,
                'total_financial_loss' => round((float) $totalFinancialLoss, 2),
                'countries_affected'   => $countriesAffected,
                // Simulated "people protected" figure (each incident touches ~150 people via awareness)
                'people_protected'     => $totalIncidents * 150,
            ],
            'by_category'  => $byCategory,
            'by_country'   => $byCountry,
            'monthly'      => $last12Months,
        ]);
    }
}
