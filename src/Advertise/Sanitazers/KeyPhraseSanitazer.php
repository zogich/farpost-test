<?php

declare(strict_types=1);

namespace App\Advertise\Sanitazers;

final class KeyPhraseSanitazer
{
    public static function sanitaze(string $keyPhrase): string
    {
        if (empty($keyPhrase)) {
            return '';
        }

        $splittedWords = explode(" ", $keyPhrase);

        $sanitazedPhrase = '';

        foreach ($splittedWords as $word) {

            $cleaned = preg_replace('/[^a-zA-Z0-9а-яА-ЯёЁ!+\-]/u', ' ', $word);

            if (preg_match('/^([!+\-])?(.+)$/', $cleaned, $matches)) {
                $prefix = $matches[1] ?? '';
                $rest   = $matches[2] ?? '';
                $rest = preg_replace('/[^a-zA-Z0-9а-яА-ЯёЁ]/u', ' ', $rest);

                $sanitazedWord = $prefix . $rest;

                if (strlen($sanitazedWord) <= 2) {
                    $sanitazedWord = sprintf("%s%s", '+', $sanitazedWord);
                }

                $sanitazedPhrase = sprintf("%s %s", $sanitazedPhrase, $sanitazedWord);
            }
        }
        return trim($sanitazedPhrase);
    }
}
