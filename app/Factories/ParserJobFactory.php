<?php

namespace App\Factories;

use App\Enums\FileType;
use App\Jobs\SaveToFile\CsvParserJob;
use App\Jobs\SaveToFile\TxtParserJob;
use App\Jobs\SaveToFile\XmlParserJob;

class ParserJobFactory
{
    public static function make(
        FileType $fileType,
        mixed $articles,
        string $filePath,
        ?int $dumpId = null,
    )
    {
        $class = match ($fileType) {
            FileType::CSV => CsvParserJob::class,
            FileType::TEXT => TxtParserJob::class,
            FileType::XML => XmlParserJob::class,
            default => throw new \InvalidArgumentException('Invalid file format specified.'),
        };
        return new $class($articles, $filePath, $dumpId);
    }
}
