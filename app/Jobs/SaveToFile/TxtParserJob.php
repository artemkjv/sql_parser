<?php

namespace App\Jobs\SaveToFile;

use App\Enums\FileType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class TxtParserJob extends SaveToFileAbstractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(mixed $articles, string $filePath, ?int $dumpId)
    {
        parent::__construct($articles, $filePath, $dumpId);
        $this->onQueue('txt-parser-job');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->saveToFile();
    }

    public function saveToFile(): void
    {
        $txtContent = '';

        foreach ($this->articles as $article) {
            $txtContent .= "{$article->title}\n{$article->content}\n\n";
        }

        File::put($this->filePath, $txtContent);
        $this->saveModel(FileType::TEXT);
    }
}
