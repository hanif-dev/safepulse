<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Knowledge Base Admin API
 *
 * Developer-only — protected by X-Admin-Token header.
 * The token must match config('services.admin.token') / env('ADMIN_TOKEN').
 *
 * Public users cannot upload — by design.
 * This avoids untrusted content polluting the RAG knowledge base.
 *
 * Endpoints:
 *   GET    /api/admin/knowledge                  → list documents
 *   POST   /api/admin/knowledge                  → create
 *   PUT    /api/admin/knowledge/{id}             → update
 *   DELETE /api/admin/knowledge/{id}             → soft-disable
 *   GET    /api/admin/knowledge/status           → system status
 */
class KnowledgeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $token         = $request->header('X-Admin-Token');
            $expectedToken = config('services.admin.token', env('ADMIN_TOKEN'));

            if (blank($expectedToken) || $token !== $expectedToken) {
                return response()->json([
                    'error'   => 'Unauthorized',
                    'message' => 'Admin token required (X-Admin-Token header).',
                ], 401);
            }

            return $next($request);
        });
    }

    public function index(Request $request): JsonResponse
    {
        $query = KnowledgeDocument::query();

        if ($topic = $request->query('topic'))   $query->where('topic', $topic);
        if ($region = $request->query('region')) $query->where('region', $region);
        if ($lang = $request->query('language')) $query->where('language', $lang);
        if ($active = $request->query('active')) $query->where('is_active', $active === '1');

        $docs = $query->orderByDesc('year')->paginate(50);

        return response()->json([
            'data' => $docs->items(),
            'meta' => [
                'current_page' => $docs->currentPage(),
                'last_page'    => $docs->lastPage(),
                'total'        => $docs->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'source'       => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:120',
            'topic'        => 'required|string|max:60',
            'region'       => 'nullable|string|max:120',
            'language'     => 'nullable|string|max:8',
            'year'         => 'nullable|integer|min:1990|max:2100',
            'source_url'   => 'nullable|url|max:1000',
            'description'  => 'nullable|string|max:5000',
            'content'      => 'nullable|string|max:5000000',
            'is_active'    => 'nullable|boolean',
        ]);

        $doc = KnowledgeDocument::create(array_merge(['is_active' => true], $data));

        return response()->json(['data' => $doc], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $doc  = KnowledgeDocument::findOrFail($id);
        $data = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'source'       => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:120',
            'topic'        => 'sometimes|string|max:60',
            'region'       => 'nullable|string|max:120',
            'language'     => 'nullable|string|max:8',
            'year'         => 'nullable|integer',
            'source_url'   => 'nullable|url|max:1000',
            'description'  => 'nullable|string|max:5000',
            'content'      => 'nullable|string|max:5000000',
            'is_active'    => 'nullable|boolean',
        ]);

        $doc->update($data);

        return response()->json(['data' => $doc]);
    }

    public function destroy(int $id): JsonResponse
    {
        $doc            = KnowledgeDocument::findOrFail($id);
        $doc->is_active = false;
        $doc->save();

        return response()->json(['message' => 'Document disabled (soft delete).']);
    }

    public function status(): JsonResponse
    {
        $totalDocs    = KnowledgeDocument::count();
        $activeDocs   = KnowledgeDocument::where('is_active', true)->count();
        $byTopic      = KnowledgeDocument::where('is_active', true)
            ->selectRaw('topic, COUNT(*) as count')
            ->groupBy('topic')
            ->pluck('count', 'topic');

        return response()->json([
            'system'        => 'SafePulse Knowledge Base',
            'version'       => '1.0',
            'total_docs'    => $totalDocs,
            'active_docs'   => $activeDocs,
            'by_topic'      => $byTopic,
            'mistral_ready' => !blank(config('services.mistral.key')),
            'rag_status'    => $activeDocs > 0 ? 'operational' : 'empty — seed required',
        ]);
    }
}
