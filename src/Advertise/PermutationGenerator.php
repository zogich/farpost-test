<?php

declare(strict_types=1);

namespace App\Advertise;



final readonly class PermutationGenerator
{
    /**
     *
     * @param array[] $groups
     * @return array[]
     */
    static function generateSearchQueries(array $groups): array
    {
        $result = [];


        $backtrack = function (int $index, array $current) use (&$backtrack, &$result, $groups) {
            if ($index === count($groups)) {
                $result[] = $current;
                return;
            }

            foreach ($groups[$index] as $item) {
                $backtrack($index + 1, array_merge($current, [$item]));
            }
        };

        $backtrack(0, []);

        return $result;
    }

    private function sortAllMinusWordsToEnd(array $keyPhrase): array
    {
        $minusWords = [];
        $otherWords = [];

        foreach ($keyPhrase as $word) {
            if (str_starts_with($word, '-')) {
                $minusWords[] = $word;
                continue;
            }
            $otherWords[] = $word;
        }

        return array_merge($otherWords, $minusWords);
    }
}
