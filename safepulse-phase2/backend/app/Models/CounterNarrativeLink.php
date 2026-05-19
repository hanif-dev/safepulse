<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterNarrativeLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'curator', 'external_url', 'content_type',
        'target_audience', 'languages', 'verified',
    ];

    protected $casts = [
        'languages' => 'array',
        'verified'  => 'boolean',
    ];
}
