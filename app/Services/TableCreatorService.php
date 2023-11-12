<?php

namespace App\Services;

use App\Helpers\StringGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TableCreatorService
{

    public function createTable(): string
    {
        $tableName = StringGenerator::generate(16);
        $createStatementPath = config('import.create_statement_path');
        $createContent = File::get($createStatementPath);
        $createContent = preg_replace('/(CREATE TABLE `)[A-Za-z0-9]+(_posts`[^*]+)/', "$1$tableName$2", $createContent);

        DB::connection('mysql_dumps')->statement($createContent);

        return $tableName;
    }

}
