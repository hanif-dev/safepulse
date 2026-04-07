<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncidentRequest;
use App\Http\Resources\IncidentResource;
use App\Models\Incident;
use Illuminate\Http\JsonResponse;

class IncidentController extends Controller
{
    public function store(StoreIncidentRequest $request): JsonResponse
    {
        $incident = Incident::create($request->validated());

        return response()->json([
            'message'  => 'Incident reported anonymously. Thank you for helping keep communities safe.',
            'incident' => new IncidentResource($incident),
        ], 201);
    }
}
