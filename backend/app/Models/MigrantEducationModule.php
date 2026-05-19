<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MigrantEducationModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'sequence', 'module_code', 'destination_country', 'sector',
        'title_localized', 'content_localized', 'video_urls',
        'pre_post_questions', 'source_attribution', 'published',
    ];

    protected $casts = [
        'title_localized'    => 'array',
        'content_localized'  => 'array',
        'video_urls'         => 'array',
        'pre_post_questions' => 'array',
        'published'          => 'boolean',
    ];

    public function scopePublished($q) { return $q->where('published', true); }
}
