<?php

namespace App\Http\Controllers;

use App\Repositories\Article\ArticleRepositoryInterface;

class ArticleController extends Controller
{

    public function __construct(
        private ArticleRepositoryInterface $dbArticleRepository,
    )
    {
    }

    public function show(int $id) {
        $article = $this->dbArticleRepository->getByUserAndId(\request()->user(), $id);
        return inertia('Article/Show', compact('article'));
    }

}
