<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepositoryInterface;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(public ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'source', 'author', 'date_from', 'date_to']);
        return response()->json($this->articleRepository->getAll($filters));
    }

    public function search(Request $request)
    {
        return response()->json($this->articleRepository->search($request->query('q')));
    }
}
