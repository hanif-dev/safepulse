<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * GET /api/articles
     * Supports: ?category=&language=&search=&page=
     */
    public function index(Request $request): JsonResponse
    {
        $query = Article::query()->whereNotNull('published_at');

        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        if ($language = $request->query('language')) {
            $query->where('language', $language);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title',   'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        $articles = $query
            ->orderByDesc('published_at')
            ->select(['id', 'slug', 'title', 'language', 'category', 'summary', 'published_at'])
            ->paginate(12);

        return response()->json([
            'data'  => ArticleResource::collection($articles->items()),
            'meta'  => [
                'current_page' => $articles->currentPage(),
                'last_page'    => $articles->lastPage(),
                'total'        => $articles->total(),
            ],
        ]);
    }

    /**
     * GET /api/articles/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        $article = Article::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        return response()->json(['data' => new ArticleResource($article)]);
    }
}
