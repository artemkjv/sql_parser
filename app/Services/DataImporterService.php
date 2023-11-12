<?php

namespace App\Services;

use App\Enums\FileType;
use App\Factories\ParserJobFactory;
use App\Models\Article;
use App\Models\Dump;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DataImporterService
{

    public function importData(string $insertQuery, string $tableName, int $userId, string $fileName): void
    {
        $insertQuery = $this->prepareInsertQuery($insertQuery, $tableName);
        DB::connection('mysql_dumps')->statement($insertQuery);

        $dump = Dump::create([
            'user_id' => $userId,
            'filename' => $fileName,
            'table_name' => $tableName . '_posts',
        ]);

        $this->importArticles($tableName . '_posts', $dump->id);

    }

    private function prepareInsertQuery(string $query, string $tableName): string
    {
        $insertQuery = preg_replace('/(INSERT INTO `)[A-Za-z0-9]+(_posts`[^*]+)/', "$1$tableName$2", $query);
        $insertQuery = str_replace("'0000-00-00 00:00:00'", 'NULL', $insertQuery);
        return preg_replace('/<a\b[^>]*>|<\/a>|<img\b[^>]*>|<\/img>/', '', $insertQuery);
    }

    private function importArticles(string $tableName, int $dumpId): void
    {
        $rowArticles = DB::connection('mysql_dumps')
            ->table($tableName)
            ->where('post_status', 'publish')
            ->get();
        $articles = collect();
        foreach ($rowArticles as $rowArticle) {
            $article = Article::create([
                'dump_id' => $dumpId,
                'title' => $rowArticle->post_title,
                'content' => $rowArticle->post_content,
            ]);
            $articles->add($article);
        }
        foreach (FileType::cases() as $fileType) {
            $directory = storage_path("app/public/$dumpId/");
            try {
                File::makeDirectory($directory, recursive: true);
            } catch (\Throwable $e) {}
            $filePath = $directory.$fileType->name.'.'.$fileType->value;
            $job = ParserJobFactory::make($fileType, $articles, $filePath, $dumpId);
            dispatch($job);
        }
    }

}
