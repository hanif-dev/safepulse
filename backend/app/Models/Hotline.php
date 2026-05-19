<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotline extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'name', 'country_iso', 'contact_channels',
        'languages_supported', 'availability', 'availability_note',
        'domains_served', 'verified', 'verified_at',
    ];

    protected $casts = [
        'contact_channels'    => 'array',
        'languages_supported' => 'array',
        'domains_served'      => 'array',
        'verified'            => 'boolean',
        'verified_at'         => 'date',
    ];

    public function scopeVerified($q) { return $q->where('verified', true); }
    public function scopeForCountry($q, string $iso) { return $q->where('country_iso', $iso); }
}
