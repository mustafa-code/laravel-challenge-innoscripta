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

        return $this->successResponse($articles);
    }

    public function search(SearchArticleRequest $request)
    {
        $filters = $request->validated();

        $query = $filters['query'];
        $articles = $this->articleRepository->search($query);

        return $this->successResponse($articles);
    }

    private function successResponse($articles)
    {
        if (method_exists($articles, 'total')) {
            return response()->success(
                data: [
                    "articles" => ArticleResource::collection($articles),
                    'pagination' => [
                        'current_page' => $articles->currentPage(),
                        'last_page' => $articles->lastPage(),
                        'per_page' => $articles->perPage(),
                        'total' => $articles->total(),
                        'next_page_url' => $articles->nextPageUrl(),
                        'prev_page_url' => $articles->previousPageUrl(),
                    ],
                ],
            );
        }

        // If it's not paginated, just return the articles
        return response()->success(
            data: [
                "articles" => ArticleResource::collection($articles)
            ],
        );
    }

}
