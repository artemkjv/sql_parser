<?php

namespace App\Jobs\SaveToFile;

use App\Enums\FileType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class XmlParserJob extends SaveToFileAbstractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(mixed $articles, string $filePath, ?int $dumpId)
    {
        parent::__construct($articles, $filePath, $dumpId);
        $this->onQueue('xml-parser-job');
    }

    /**
     * Create a new job instance.
     */

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->saveToFile();
    }

    public function saveToFile(): void
    {
        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->startDocument();
        $xml->startElement('articles');

        foreach ($this->articles as $article) {
            $xml->startElement('article');
            $xml->writeElement('title', $article->title);
            $xml->writeElement('text', $article->content);
            $xml->endElement();
        }

        $xml->endElement();
        $xml->endDocument();

        \Illuminate\Support\Facades\File::put($this->filePath, $xml->outputMemory());

        $this->saveModel(FileType::XML);
    }
}
