<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PFAResource extends Model
{
    use HasFactory;

    protected $table = 'pfa_resources';

    protected $fillable = [
        'action', 'topic', 'content_localized', 'referral_targets',
    ];

    protected $casts = [
        'content_localized' => 'array',
        'referral_targets'  => 'array',
    ];
}
