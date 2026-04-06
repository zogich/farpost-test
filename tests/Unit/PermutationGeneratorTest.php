<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Advertise\PermutationGenerator;
use PHPUnit\Framework\TestCase;

final class PermutationGeneratorTest extends TestCase

{
    public function testGeneratePermutation(): void
    {
        $input = [
            ['a', 'b', 'c'],
            ['d', 'e']
        ];

        $result = PermutationGenerator::generateSearchQueries($input);

        $expected = [
            ['a', 'd'],
            ['a', 'e'],
            ['b', 'd'],
            ['b', 'e'],
            ['c', 'd'],
            ['c', 'e']
        ];

        $this->assertEquals($expected, $result);
    }

    public function testMinusWordsMoveToTheEndOfPermutation(): void
    {
        $input = [
            ['a -b', 'c -d', 'e'],
            ['f', '-g h'],
        ];

        $result = PermutationGenerator::generateSearchQueries($input);

        $expected = [
            ['a', 'f', '-b'],
            ['a', 'h', '-b', '-g'],
            ['c', 'f', '-d'],
            ['c', 'h', '-d', '-g'],
            ['e', 'f'],
            ['e', 'h', '-g'],
        ];

        $this->assertEquals($expected, $result);
    }

    public function testRemoveDuplicateWordsInPermutations(): void
    {
        $input = [
            ['a -b', '-c d'],
            ['a -f', 'g -c']
        ];

        $result = PermutationGenerator::generateSearchQueries($input);

        $expected = [
            ['a', '-b', '-f'],
            ['a', 'g', '-b', '-c'],
            ['d', 'a', '-c', '-f'],
            ['d', 'g', '-c'],
        ];

        $this->assertEquals($expected, $result);
    }

    public function testRemoveIntersectionsBetweenSimpleWordsAndMinusWords(): void
    {
        $input = [
            ['a -b', '-c d'],
            ['-a b', 'c -d'],
        ];

        $result = PermutationGenerator::generateSearchQueries($input);
        $expected = [
            ['a', '-b'],
            ['a', 'c', '-b', '-d'],
            ['d', 'b', '-c', '-a'],
            ['d', '-c']

        ];
        $this->assertEquals($expected, $result);
    }
}
