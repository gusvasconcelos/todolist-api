<?php

namespace App\Http\Controllers;

use App\Http\Requests\News\GetArticlesRequest;
use App\Services\News\NewsService;

class NewsController extends Controller
{
    public function __construct(
        protected NewsService $newsService
    ) {

    }

    public function getArticles(GetArticlesRequest $request)
    {
        $validated = $request->validated();

        $articles = $this->newsService->getArticles(collect($validated));

        return response()->json($articles);
    }
}
