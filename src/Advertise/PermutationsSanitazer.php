<?php

declare(strict_types=1);

namespace App\Advertise;

final readonly class PermutationsSanitazer
{
    public static function sanitazePermutation(array $permutation)
    {

        $simpleWords = [];
        $minusWords = [];

        foreach ($permutation as $phrase) {
            $words = explode(" ", $phrase);
            foreach ($words as $word) {
                if (str_starts_with($word, '-')) {
                    if (!in_array(
                        $word,
                        $minusWords,
                        true
                    ) && !in_array(ltrim($word, '-'), $minusWords, true)) {
                        $minusWords[] = $word;
                    }
                    continue;
                }

                if (!in_array('-' . $word, $minusWords, true) && !in_array($word, $simpleWords, true)) {
                    $simpleWords[] = $word;
                }
            }
        }

        return array_merge($simpleWords, $minusWords);
    }
}
