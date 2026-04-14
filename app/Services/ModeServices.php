<?php

namespace App\Services;

class ModeServices
{
    public static function GET(): string
    {
        $mode = (string) config('custom.system_mode') ?? '';
        return $mode;
    }
}

