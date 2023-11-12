<?php

namespace App\Repositories\Dump;

use App\Models\Dump;
use App\Models\User;

interface DumpRepositoryInterface
{

    public function paginateByUser(User $user, int $perPage = 10);

    public function getByUserAndId(User $user, int $id);

    public function deleteByUserAndId(User $user, int $id);

    public function getByUserAndIds(User $user, array $ids);

}
