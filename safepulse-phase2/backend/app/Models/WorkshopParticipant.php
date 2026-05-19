<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkshopParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_session_id', 'participant_code',
        'pre_assessment', 'post_assessment',
        'certificate_hash', 'certificate_issued_at',
    ];

    protected $casts = [
        'pre_assessment'        => 'array',
        'post_assessment'       => 'array',
        'certificate_issued_at' => 'datetime',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(WorkshopSession::class, 'workshop_session_id');
    }

    /**
     * Calculate knowledge uplift in percentage points between pre/post.
     */
    public function uplift(): ?int
    {
        if (! $this->pre_assessment || ! $this->post_assessment) {
            return null;
        }
        $pre  = $this->pre_assessment['score'] ?? null;
        $post = $this->post_assessment['score'] ?? null;
        return ($pre !== null && $post !== null) ? ($post - $pre) : null;
    }
}
