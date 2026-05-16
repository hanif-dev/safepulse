<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'source', 'organization', 'topic', 'region', 'language',
        'year', 'source_url', 'description', 'content', 'file_path', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'year'      => 'integer',
    ];
}
