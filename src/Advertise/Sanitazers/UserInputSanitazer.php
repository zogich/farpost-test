<?php

declare(strict_types=1);

namespace App\Advertise\Sanitazers;

final readonly class UserInputSanitazer
{

    /**
     * @param string[] $linesWithPhrases
     * @return array[]
     */
    public function sanitaze(array $linesWithPhrases): array
    {
        $sanitazedLines = [];

        foreach ($linesWithPhrases as $line) {
            $sanitazedLines[] = array_map(
                fn(string $word) => KeyPhraseSanitazer::sanitaze($word),
                array_unique($this->parseLine($line))
            );
        }

        $crossMinus = [];
        foreach ($sanitazedLines as $line) {
            $crossMinus[] = $this->applyCrossMinus($line);
        }

        return $crossMinus;
    }

    /**
     *
     * @param string[] $phrases
     * @return string[]
     */
    private function applyCrossMinus(array $phrases): array
    {
        $tokenized = [];
        foreach ($phrases as $index => $phrase) {
            $tokenized[$index] = explode(' ', $phrase);
        }

        $indices = array_keys($tokenized);
        usort($indices, function ($a, $b) use ($tokenized) {
            return count($tokenized[$a]) - count($tokenized[$b]);
        });

        $minusWords = [];
        foreach ($phrases as $index => $phrase) {
            $minusWords[$index] = [];
        }

        for ($i = 0; $i < count($indices); $i++) {
            for ($j = $i + 1; $j < count($indices); $j++) {
                $shortIdx = $indices[$i];
                $longIdx = $indices[$j];
                $shortTokens = $tokenized[$shortIdx];
                $longTokens = $tokenized[$longIdx];

                $isPrefix = true;
                if (count($shortTokens) <= count($longTokens)) {
                    for ($k = 0; $k < count($shortTokens); $k++) {
                        if ($shortTokens[$k] !== $longTokens[$k]) {
                            $isPrefix = false;
                            break;
                        }
                    }
                } else {
                    $isPrefix = false;
                }

                if ($isPrefix) {

                    $extraTokens = array_slice($longTokens, count($shortTokens));
                    foreach ($extraTokens as $token) {
                        if (!in_array($token, $minusWords[$shortIdx])) {
                            $minusWords[$shortIdx][] = $token;
                        }
                    }
                }
            }
        }

        $result = [];
        foreach ($phrases as $index => $phrase) {
            $tokens = explode(' ', $phrase);

            if (!empty($minusWords[$index])) {

                $minusTokens = array_map(function ($word) {
                    return '-' . $word;
                }, $minusWords[$index]);

                $result[] = implode(' ', array_merge($tokens, $minusTokens));
            } else {
                $result[] = $phrase;
            }
        }

        return $result;
    }
    private function parseLine(string $line): array
    {
        if (empty(trim($line))) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $line)));
    }
}
