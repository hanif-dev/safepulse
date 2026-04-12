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
     *
     * Fully DB-agnostic: works with SQLite (Codespaces/dev)
     * AND MySQL (EC2/GCP production) without any code change.
     */
    public function overview(): JsonResponse
    {
        // ── By category ──────────────────────────────────────────────────
        $byCategory = Incident::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        // ── By country (top 30) ──────────────────────────────────────────
        $byCountry = Incident::select('country', DB::raw('count(*) as total'))
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(30)
            ->pluck('total', 'country');

        // ── Last 12 months — DB-agnostic expression ───────────────────────
        $driver = DB::connection()->getDriverName();   // 'sqlite' or 'mysql'

        $monthExpr = $driver === 'sqlite'
            ? DB::raw("strftime('%Y-%m', created_at) as month")
            : DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month");

        $last12Months = Incident::select($monthExpr, DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        // ── Summary counts ───────────────────────────────────────────────
        $totalIncidents      = Incident::count();
        $highImpact          = Incident::where('health_impact_level', 'high')->count();
        $totalFinancialLoss  = Incident::sum('financial_loss_estimate');
        $countriesAffected   = Incident::distinct()->count('country');

        return response()->json([
            'summary' => [
                'total_incidents'      => $totalIncidents,
                'high_impact'          => $highImpact,
                'total_financial_loss' => round((float) $totalFinancialLoss, 2),
                'countries_affected'   => $countriesAffected,
                // Tiap incident mewakili ~150 orang yang terlindungi via awareness
                'people_protected'     => $totalIncidents * 150,
            ],
            'by_category' => $byCategory,
            'by_country'  => $byCountry,
            'monthly'     => $last12Months,
        ]);
    }
}
