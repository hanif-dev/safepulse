<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserProfile extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'session_token', 'role', 'country_iso', 'province_code',
        'locale', 'consent_flags', 'expires_at',
    ];

    protected $casts = [
        'consent_flags' => 'array',
        'expires_at'    => 'datetime',
    ];

    public function assessments(): HasMany
    {
        return $this->hasMany(DeepAssessment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
