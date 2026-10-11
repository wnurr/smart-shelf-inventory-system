<?php

namespace App\Support;

class TextFormatter
{
    /**
     * Convert text to uppercase.
     *
     * Example:
     * f001 -> F001
     * s1   -> S1
     */
    public static function uppercase(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return mb_strtoupper(
            trim($value),
            'UTF-8'
        );
    }


    /**
     * Convert text to title case.
     *
     * Example:
     * nails -> Nails
     * hex bolt -> Hex Bolt
     * wan nur humairah -> Wan Nur Humairah
     */
    public static function titleCase(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return mb_convert_case(
            trim($value),
            MB_CASE_TITLE,
            'UTF-8'
        );
    }
}