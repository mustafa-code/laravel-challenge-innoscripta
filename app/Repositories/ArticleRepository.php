<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function getAll(array $filters): Collection
    {
        $query = Article::query();

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (!empty($filters['source'])) {
            $query->whereHas('source', function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['source'] . '%');
            });
        }
        if (!empty($filters['author'])) {
            $query->where('author', 'like', '%' . $filters['author'] . '%');
        }
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $query->whereBetween('published_at', [$filters['date_from'], $filters['date_to']]);
        }

        return $query->get();
    }

    public function search(string $query): Collection
    {
        return Article::where('title', 'like', '%' . $query . '%')
            ->orWhere('content', 'like', '%' . $query . '%')
            ->get();
    }

    public function store(array $articlesData): bool
    {
        return DB::table('articles')->insert($articlesData);
    }
}
