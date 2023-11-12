<?php

namespace App\Services;

class SqlParserService
{

    public function extractInsertQuery(string $content): string
    {
        $matches = [];
        preg_match('/INSERT INTO `[A-Za-z0-9]+_posts` \([^)]*\).*?\);/s', $content, $matches);

        return array_shift($matches);
    }

}
