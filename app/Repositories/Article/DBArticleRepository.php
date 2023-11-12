<?php

namespace App\Repositories\Article;

use App\Http\Resources\Article\ArticleResource;
use App\Models\User;

class DBArticleRepository implements ArticleRepositoryInterface
{

    public function getByUserAndId(User $user, int $id)
    {
        return ArticleResource::make($user->articles()->findOrFail($id))
            ->resolve();
    }

    public function getByUserAndDumpIds(User $user, array $ids)
    {
        return $user->articles()
            ->whereIn('dump_id', $ids)
            ->get();
    }
}
