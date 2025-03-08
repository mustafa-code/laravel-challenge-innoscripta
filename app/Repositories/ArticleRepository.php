<?php

namespace App\Repositories;

use App\Models\Article;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator
    {
        $query = Article::with('source');

        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

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

        return $query->paginate();
    }

    public function search(string $query): LengthAwarePaginator
    {
        return Article::with('source')->where('title', 'like', '%' . $query . '%')
            ->orWhere('content', 'like', '%' . $query . '%')
            ->paginate();
    }

    public function store(array $articlesData): bool
    {
        // Much faster than Model::insert function.
        try {
            // Filter out the duplicated URLs...
            $existingUrls = DB::table('articles')
                ->whereIn('url', array_column($articlesData, 'url'))
                ->pluck('url')
                ->toArray();

            $validArticles = array_filter($articlesData, function ($article) use ($existingUrls) {
                return isset($article['url']) && !in_array($article['url'], $existingUrls);
            });

            if (!empty($validArticles)) {
                return DB::table('articles')->insert($validArticles);
            }
            return true;
        } catch (Exception $e) {
            report("Exception occured while inserting articles, " . $e->getMessage());
            // Insert the items one by one
            foreach ($articlesData as $item) {
                try {
                    Article::create($item);
                } catch (Exception $ex) {
                    report("Failed to store the article one by one. ". $ex->getMessage());
                }
            }
        }
        return false;
    }
}
