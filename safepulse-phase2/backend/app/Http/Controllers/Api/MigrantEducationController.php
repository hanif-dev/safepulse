<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\MigrantEducationModule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Pre-Departure Migrant Worker Education endpoints.
 *
 * Curriculum aligned with:
 *  - BP2MI Revised PDO Modules 2022
 *  - IOM Comprehensive Information and Orientation Programme (CIOP)
 *  - UU 18/2017 on Migrant Worker Protection
 *  - 2024 Palm Oil Sector module (BP2MI + IOM + Consumer Goods Forum)
 *
 * Module sequence:
 *   M1_VERIFIKASI  — Verify job offer via BP2MI SISKOTKLN
 *   M2_HAK          — Worker rights per destination
 *   M3_TPPO_SIGNS   — Trafficking warning signs
 *   M4_KBRI         — Embassy emergency contacts
 *   M5_SURVIVOR     — Anonymized survivor stories
 *   M6_FINANCIAL    — Remittance and fraud awareness
 *   M7_VE_AWARE     — Violent extremism awareness (BP2MI 2022 addition)
 */
class MigrantEducationController extends Controller
{
    public function curriculum(Request $request): JsonResponse
    {
        $data = $request->validate([
            'to'     => 'required|size:2',          // destination country ISO
            'sector' => 'nullable|string|max:32',
            'lang'   => 'nullable|string|max:8',
        ]);

        $modules = MigrantEducationModule::published()
            ->where('destination_country', $data['to'])
            ->when($data['sector'] ?? null, fn ($q, $s) => $q->where('sector', $s))
            ->orderBy('sequence')
            ->get();

        $locale = $data['lang'] ?? 'id';

        return response()->json([
            'destination_country' => $data['to'],
            'sector'              => $data['sector'] ?? null,
            'locale'              => $locale,
            'modules' => $modules->map(fn ($m) => [
                'sequence'     => $m->sequence,
                'module_code'  => $m->module_code,
                'title'        => $m->title_localized[$locale] ?? $m->title_localized['en'] ?? '',
                'has_video'    => ! empty($m->video_urls),
                'source'       => $m->source_attribution,
            ]),
        ]);
    }

    public function module(int $id, Request $request): JsonResponse
    {
        $module = MigrantEducationModule::published()->findOrFail($id);
        $locale = $request->query('lang', 'id');

        AuditLog::record('migrant.module_view', 'anonymous', [
            'module_code' => $module->module_code,
            'destination' => $module->destination_country,
            'locale'      => $locale,
        ]);

        return response()->json([
            'sequence'    => $module->sequence,
            'module_code' => $module->module_code,
            'title'       => $module->title_localized[$locale] ?? $module->title_localized['en'] ?? '',
            'content'     => $module->content_localized[$locale] ?? $module->content_localized['en'] ?? '',
            'video_urls'  => $module->video_urls,
            'pre_post_questions' => $module->pre_post_questions,
            'source'      => $module->source_attribution,
        ]);
    }

    public function preAssessment(Request $request): JsonResponse
    {
        $data = $request->validate([
            'curriculum_code' => 'required|string',
            'answers'         => 'required|array',
        ]);

        $score = $this->scoreAssessment($data['answers']);

        AuditLog::record('migrant.pre_assessment', 'anonymous', [
            'curriculum' => $data['curriculum_code'],
            'score'      => $score,
        ]);

        return response()->json([
            'score'         => $score,
            'feedback_key'  => $score < 50 ? 'high_priority' : 'standard',
            'recommended_modules' => $this->modulesToReview($data['answers']),
        ]);
    }

    public function postAssessment(Request $request): JsonResponse
    {
        $data = $request->validate([
            'curriculum_code' => 'required|string',
            'pre_score'       => 'required|integer|min:0|max:100',
            'answers'         => 'required|array',
        ]);

        $postScore = $this->scoreAssessment($data['answers']);
        $uplift    = $postScore - $data['pre_score'];

        AuditLog::record('migrant.post_assessment', 'anonymous', [
            'curriculum' => $data['curriculum_code'],
            'pre_score'  => $data['pre_score'],
            'post_score' => $postScore,
            'uplift'     => $uplift,
        ]);

        return response()->json([
            'pre_score'  => $data['pre_score'],
            'post_score' => $postScore,
            'uplift'     => $uplift,
            'passed'     => $postScore >= 70,
        ]);
    }

    private function scoreAssessment(array $answers): int
    {
        $correct = 0;
        $total   = count($answers);
        foreach ($answers as $a) {
            if (($a['correct'] ?? false) === true) {
                $correct++;
            }
        }
        return $total > 0 ? (int) round(($correct / $total) * 100) : 0;
    }

    private function modulesToReview(array $answers): array
    {
        $weak = [];
        foreach ($answers as $a) {
            if (($a['correct'] ?? false) === false && isset($a['module_code'])) {
                $weak[] = $a['module_code'];
            }
        }
        return array_values(array_unique($weak));
    }
}
