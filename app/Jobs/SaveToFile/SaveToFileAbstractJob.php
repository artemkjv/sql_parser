<?php

namespace App\Jobs\SaveToFile;

use App\Enums\FileType;
use App\Events\ArticlesExported;
use App\Http\Resources\Article\ArticleResource;
use App\Models\File;

abstract class SaveToFileAbstractJob
{

    public function __construct(
        protected mixed $articles,
        protected string $filePath,
        protected ?int $dumpId,
    )
    {
    }

    protected function saveToFile(): void{}

    protected function saveModel(FileType $type) {
        $filePath = "/storage/".str_replace(storage_path('app/public/'), '', $this->filePath);
        $file = File::create([
            'path' => $filePath,
            'dump_id' => $this->dumpId,
            'type' => $type->name
        ]);
        $this->notifyUser();
        return $file;
    }

    protected function notifyUser(): void{
        $firstArticle = $this->articles->first();
        $userId = $firstArticle->dump->user_id;
        ArticlesExported::broadcast($userId, 'success');
    }

}
