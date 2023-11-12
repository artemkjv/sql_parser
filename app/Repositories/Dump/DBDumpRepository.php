<?php

namespace App\Repositories\Dump;

use App\Http\Resources\Dump\DumpCollection;
use App\Http\Resources\Dump\DumpResource;
use App\Http\Resources\Dump\DumpWithArticlesAndFileResource;

use App\Models\User;

class DBDumpRepository implements DumpRepositoryInterface
{

    public function paginateByUser(User $user, int $perPage = 10)
    {
        return DumpCollection::make(
            $user->dumps()
                ->orderByDesc('id')
                ->paginate($perPage)
        )->resolve();
    }

    public function getByUserAndId(User $user, int $id)
    {
        return DumpWithArticlesAndFileResource::make(
            $user->dumps()
                ->with('articles')
                ->findOrFail($id)
        )->resolve();
    }

    public function deleteByUserAndId(User $user, int $id)
    {
        $user->dumps()
            ->findOrFail($id)
            ->delete($id);
    }

    public function getByUserAndIds(User $user, array $ids)
    {
        return DumpWithArticlesAndFileResource::collection($user->dumps()
            ->with('articles')
            ->whereIn('dumps.id', $ids)
            ->get()
        )->resolve();
    }

}
