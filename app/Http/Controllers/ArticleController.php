<?php

namespace App\Http\Controllers;

use App\Http\Requests\AllArticleRequest;
use App\Http\Requests\SearchArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Repositories\ArticleRepositoryInterface;

class ArticleController extends Controller
{
    public function __construct(public ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index(AllArticleRequest $request)
    {
        $filters = $request->validated();

        $articles = $this->articleRepository->getAll($filters);

        return response()->success(
            data: ArticleResource::collection($articles),
        );
    }

    public function search(SearchArticleRequest $request)
    {
        $filters = $request->validated();

        $query = $filters['query'];
        $articles = $this->articleRepository->search($query);

        return response()->success(
            data: ArticleResource::collection($articles),
        );
    }
}
