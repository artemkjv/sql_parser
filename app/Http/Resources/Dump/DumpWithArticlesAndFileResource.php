<?php

namespace App\Http\Resources\Dump;

use App\Http\Resources\Article\ArticleResource;
use App\Http\Resources\File\FileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DumpWithArticlesAndFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'filename' => $this->filename,
            'articles' => ArticleResource::collection($this->articles)->resolve(),
            'files' => FileResource::collection($this->files)->resolve(),
        ];
    }
}
