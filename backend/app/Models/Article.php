<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'title', 'language', 'category',
        'summary', 'body_markdown', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
