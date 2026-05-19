<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeepAssessment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_profile_id', 'crime_domain', 'answers',
        'risk_signals', 'mode', 'completion_pct',
    ];

    protected $casts = [
        'answers'      => 'encrypted:array',
        'risk_signals' => 'array',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id');
    }
}
