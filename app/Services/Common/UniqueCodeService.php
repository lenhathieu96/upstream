<?php

namespace App\Services\Common;

class UniqueCodeService
{
    public static function generate(string $prefix = ''): string
    {
        return $prefix . now()->format('YmdHisv');
    }
}