<?php

namespace App\Http\Controllers;

use App\Http\Requests\AllArticleRequest;
use App\Http\Requests\SearchArticleRequest;
use App\Repositories\ArticleRepositoryInterface;
use Illuminate\Http\Request;

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

        return response()->json($articles);
    }

    public function search(SearchArticleRequest $request)
    {
        $filters = $request->validated();

        $query = $filters['query'];
        $articles = $this->articleRepository->search($query);

        return response()->json($articles);
    }
}
