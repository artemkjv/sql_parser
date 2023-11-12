<?php

namespace App\Jobs;

use App\Events\ParseDumpFailed;
use App\Helpers\StringGenerator;
use App\Models\Article;
use App\Models\Dump;
use App\Services\DataImporterService;
use App\Services\SqlParserService;
use App\Services\TableCreatorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class ParseSqlDump implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $filePath,
        private readonly string $fileName,
        private readonly int $userId,
    )
    {
        $this->onQueue('parse-sql-dump');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $content = File::get($this->filePath);
            $sqlParser = new SqlParserService();
            $insertQuery = $sqlParser->extractInsertQuery($content);

            $tableCreator = new TableCreatorService();
            $tableName = $tableCreator->createTable();

            $dataImporter = new DataImporterService();
            $dataImporter->importData($insertQuery, $tableName, $this->userId, $this->fileName);
        } catch (\Throwable $e){
            ParseDumpFailed::broadcast($this->userId, 'Something went bad :(');
        }
    }

}
