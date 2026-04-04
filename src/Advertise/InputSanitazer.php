<?php

declare(strict_types=1);

namespace App\Advertise;

final class InputSanitazer
{
    public static function sanitaze(string $word): string
    {
        if (empty($word)) {
            return '';
        }

        $cleaned = preg_replace('/[^a-zA-Z0-9!+\-]/u', ' ', $word);

        if (preg_match('/^([!+\-])?(.+)$/', $cleaned, $matches)) {
            $prefix = $matches[1] ?? '';
            $rest   = $matches[2] ?? '';

            $rest = preg_replace('/[^a-zA-Z0-9]/', '', $rest);

            return $prefix . $rest;
        }

        return '';
    }
}
