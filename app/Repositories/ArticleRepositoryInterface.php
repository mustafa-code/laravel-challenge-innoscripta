<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator;
    public function search(string $query): LengthAwarePaginator;
    public function store(array $articlesData): bool;
}
