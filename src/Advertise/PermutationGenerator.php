<?php

declare(strict_types=1);

namespace App\Advertise;

use App\Advertise\Sanitazers\PermutationsSanitazer;

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

        foreach ($result as &$permutation) {
            $permutation = PermutationsSanitazer::sanitazePermutation($permutation);
        }
        return $result;
    }
}
