<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'category'               => $this->category,
            'country'                => $this->country,
            'age_group'              => $this->age_group,
            'health_impact_level'    => $this->health_impact_level,
            'financial_loss_estimate'=> $this->financial_loss_estimate,
            'created_at'             => $this->created_at->toIso8601String(),
        ];
    }
}
