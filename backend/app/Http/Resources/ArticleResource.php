<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'slug'         => $this->slug,
            'title'        => $this->title,
            'language'     => $this->language,
            'category'     => $this->category,
            'summary'      => $this->summary,
            'body_markdown'=> $this->when($this->body_markdown !== null, $this->body_markdown),
            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
