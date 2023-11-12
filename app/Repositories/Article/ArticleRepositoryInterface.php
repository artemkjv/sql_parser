<?php

namespace App\Repositories\Article;

use App\Models\User;

interface ArticleRepositoryInterface
{

    public function getByUserAndId(User $user, int $id);

    public function getByUserAndDumpIds(User $user, array $ids);

}
