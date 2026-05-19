<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\WorkshopParticipant;
use App\Models\WorkshopSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * CyberShield ASEAN 2.0 Workshop Integration.
 *
 * Facilitator-side: create session, manage participants.
 * Participant-side: anonymous join via session code, pre/post assessments.
 * Public-side: certificate verification by SHA-256 hash.
 *
 * Privacy: participants identified ONLY by random participant_code,
 * never by name/email/phone. PII is never collected by SafePulse.
 */
class WorkshopController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────
    // FACILITATOR (admin-token protected)
    // ──────────────────────────────────────────────────────────────────────

    public function createSession(Request $request): JsonResponse
    {
        $this->requireAdminToken($request);

        $data = $request->validate([
            'workshop_name'         => 'nullable|string|max:120',
            'facilitator_name'      => 'required|string|max:120',
            'host_organization'     => 'nullable|string|max:120',
            'held_on'               => 'required|date',
            'location'              => 'nullable|string|max:200',
            'expected_participants' => 'nullable|integer|min:1|max:1000',
            'modules_covered'       => 'required|array|min:1',
        ]);

        $session = WorkshopSession::create(array_merge($data, [
            'session_code' => strtoupper(Str::random(8)),
        ]));

        AuditLog::record('workshop.create', 'facilitator', [
            'session_code'    => $session->session_code,
            'modules_covered' => $session->modules_covered,
        ]);

        return response()->json([
            'session_code' => $session->session_code,
            'join_url'     => url("/workshop/join/{$session->session_code}"),
            'created_at'   => $session->created_at,
        ], 201);
    }

    // ──────────────────────────────────────────────────────────────────────
    // PARTICIPANT (anonymous)
    // ──────────────────────────────────────────────────────────────────────

    public function joinSession(string $code): JsonResponse
    {
        $session = WorkshopSession::where('session_code', $code)
            ->where('is_active', true)
            ->firstOrFail();

        $participant = WorkshopParticipant::create([
            'workshop_session_id' => $session->id,
            'participant_code'    => strtoupper(Str::random(12)),
        ]);

        AuditLog::record('workshop.join', 'participant', [
            'session_code' => $session->session_code,
        ]);

        return response()->json([
            'participant_code' => $participant->participant_code,
            'session' => [
                'workshop_name'    => $session->workshop_name,
                'facilitator'      => $session->facilitator_name,
                'modules_covered'  => $session->modules_covered,
                'held_on'          => $session->held_on,
            ],
        ], 201);
    }

    public function submitAssessment(Request $request): JsonResponse
    {
        $data = $request->validate([
            'participant_code' => 'required|string|size:12',
            'kind'             => 'required|in:pre,post',
            'answers'          => 'required|array',
            'score'            => 'required|integer|min:0|max:100',
            'self_efficacy'    => 'nullable|numeric|min:1|max:5',
        ]);

        $participant = WorkshopParticipant::where('participant_code', $data['participant_code'])
            ->firstOrFail();

        $payload = [
            'answers'       => $data['answers'],
            'score'         => $data['score'],
            'self_efficacy' => $data['self_efficacy'] ?? null,
            'submitted_at'  => now(),
        ];

        if ($data['kind'] === 'pre') {
            $participant->pre_assessment = $payload;
        } else {
            $participant->post_assessment = $payload;
        }
        $participant->save();

        return response()->json([
            'kind'           => $data['kind'],
            'score'          => $data['score'],
            'uplift'         => $participant->uplift(),
            'can_certificate' => $data['kind'] === 'post' && $data['score'] >= 70,
        ]);
    }

    public function issueCertificate(string $participantCode): JsonResponse
    {
        $participant = WorkshopParticipant::where('participant_code', $participantCode)
            ->with('session')
            ->firstOrFail();

        if (! $participant->post_assessment || ($participant->post_assessment['score'] ?? 0) < 70) {
            return response()->json([
                'error' => 'Certificate requires post-assessment score >= 70',
            ], 400);
        }

        $payload = json_encode([
            'participant_code' => $participant->participant_code,
            'workshop_name'    => $participant->session->workshop_name,
            'facilitator'      => $participant->session->facilitator_name,
            'date'             => $participant->session->held_on->toIso8601String(),
            'modules'          => $participant->session->modules_covered,
        ]);

        $participant->certificate_hash      = hash('sha256', $payload);
        $participant->certificate_issued_at = now();
        $participant->save();

        AuditLog::record('workshop.certificate_issue', 'participant', [
            'hash' => $participant->certificate_hash,
        ]);

        return response()->json([
            'certificate_hash' => $participant->certificate_hash,
            'verify_url'       => url("/api/v2/workshop/certificates/verify/{$participant->certificate_hash}"),
            'issued_at'        => $participant->certificate_issued_at,
            'pdf_url'          => url("/workshop/certificate/pdf/{$participant->certificate_hash}"),
        ]);
    }

    public function verifyCertificate(string $hash): JsonResponse
    {
        $participant = WorkshopParticipant::where('certificate_hash', $hash)
            ->with('session')
            ->first();

        if (! $participant) {
            return response()->json(['valid' => false], 404);
        }

        return response()->json([
            'valid'         => true,
            'workshop_name' => $participant->session->workshop_name,
            'facilitator'   => $participant->session->facilitator_name,
            'held_on'       => $participant->session->held_on,
            'modules'       => $participant->session->modules_covered,
            'issued_at'     => $participant->certificate_issued_at,
            'note'          => 'Certificate of participation, not professional qualification.',
        ]);
    }

    private function requireAdminToken(Request $request): void
    {
        $token = $request->header('X-Admin-Token');
        $expected = config('services.admin.token', env('ADMIN_TOKEN'));

        abort_if(blank($expected) || $token !== $expected, 401, 'Admin token required');
    }
}
