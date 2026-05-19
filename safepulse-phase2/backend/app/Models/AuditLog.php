<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'action', 'actor_role', 'metadata', 'occurred_at',
    ];

    protected $casts = [
        'metadata'    => 'array',
        'occurred_at' => 'datetime',
    ];

    /**
     * Log helper — privacy-preserving by design.
     * Never call with PII in $metadata.
     */
    public static function record(string $action, string $actorRole, array $metadata = []): void
    {
        // Strip any accidental PII keys
        $forbidden = ['name', 'email', 'phone', 'ip', 'user_id', 'session_token'];
        foreach ($forbidden as $key) {
            unset($metadata[$key]);
        }

        self::create([
            'action'      => $action,
            'actor_role'  => $actorRole,
            'metadata'    => $metadata,
            'occurred_at' => now(),
        ]);
    }
}
