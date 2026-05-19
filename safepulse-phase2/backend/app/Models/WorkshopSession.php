<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkshopSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_code', 'workshop_name', 'facilitator_name',
        'host_organization', 'held_on', 'location',
        'expected_participants', 'modules_covered', 'is_active',
    ];

    protected $casts = [
        'modules_covered' => 'array',
        'held_on'         => 'date',
        'is_active'       => 'boolean',
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(WorkshopParticipant::class);
    }
}
