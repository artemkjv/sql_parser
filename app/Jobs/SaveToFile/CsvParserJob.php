<?php

namespace App\Jobs\SaveToFile;

use App\Enums\FileType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CsvParserJob extends SaveToFileAbstractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(mixed $articles, string $filePath, ?int $dumpId)
    {
        parent::__construct($articles, $filePath, $dumpId);
        $this->onQueue('csv-parser-job');
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
        $csvFile = fopen($this->filePath, 'w');

        fputcsv($csvFile, ['Title', 'Text']);

        foreach ($this->articles as $article) {
            fputcsv($csvFile, [$article->title, $article->content]);
        }

        fclose($csvFile);

        $this->saveModel(FileType::CSV);

    }
}
