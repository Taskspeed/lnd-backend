<?php

namespace App\Traits;
use App\Models\RSP\yOffice;

trait ResolvesOfficeAbbr
{
    protected function officeAbbr(?string $office): ?string
    {
        if (blank($office)) {
            return null;
        }

        static $cache = [];

        $key = trim($office);

        return $cache[$key] ??= yOffice::where('Descriptions', $key)->value('Abbr');
    }
}