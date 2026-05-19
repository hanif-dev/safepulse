<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurvivorStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'anonymous_handle', 'crime_domain', 'country_iso', 'locale',
        'story_text', 'video_url', 'consent_granted_at',
        'consent_review_due', 'moderation_status',
    ];

    protected $casts = [
        'consent_granted_at' => 'date',
        'consent_review_due' => 'date',
    ];

    public function scopeApproved($q) { return $q->where('moderation_status', 'approved'); }
}
