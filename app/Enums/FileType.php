<?php

namespace App\Enums;

enum FileType: string
{

    case XML = 'xml';
    case CSV = 'csv';
    case TEXT = 'txt';

    public static function random(): self {
        $arr = [];
        $arrDT = self::cases();

        for($i = 0; $i < self::count(); $i++)
            $arr[$i] = $arrDT[$i]->value;

        $i = array_rand($arr, 1);

        return $arrDT[$i];
    }

    public static function count(): int {
        return count(self::cases());
    }

    public static function values(): array {
        $cases = self::cases();
        return array_column($cases, 'value');
    }

}
