<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Article;

interface ArticleRepositoryInterface
{
    public function getAll(array $filters): Collection;
    public function search(string $query): Collection;
    public function store(array $articlesData): bool;
}
