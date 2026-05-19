<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoveryPathway extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'crime_domain', 'title', 'summary',
        'milestones', 'templates', 'hotlines', 'published',
    ];

    protected $casts = [
        'title'      => 'array',
        'summary'    => 'array',
        'milestones' => 'array',
        'templates'  => 'array',
        'hotlines'   => 'array',
        'published'  => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function localized(string $field, string $locale = 'id'): string
    {
        return $this->{$field}[$locale] ?? $this->{$field}['en'] ?? '';
    }
}
