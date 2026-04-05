<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Advertise\UserInputSanitazer;
use PHPUnit\Framework\TestCase;

final class UserInputSanitazerTest extends TestCase
{
    private UserInputSanitazer $sanitazer;

    protected function setUp(): void
    {
        $this->sanitazer = new UserInputSanitazer();
    }

    /**
     * @test
     */
    public function testRemoveDuplicates(): void
    {
        $input = ['one, one, two'];

        $result = $this->sanitazer->sanitaze($input);

        $expectedOutput = [['one', 'two']];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testRemoveUnacceptableSymbols(): void
    {
        $input = ['one#, %two, th*ree, fo-ur'];

        $result = $this->sanitazer->sanitaze($input);

        $expectedOutput = [['one', 'two', 'th ree', 'fo ur']];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testAddPlusBeforeWordsConsistingLessTwoSymbols(): void
    {
        $input = ['on sale, on purchase'];

        $result = $this->sanitazer->sanitaze($input);

        $expectedOutput = [['+on sale', '+on purchase']];
        $this->assertEquals($expectedOutput, $result);
    }

    public function testCorrectApplyCrossMinus(): void
    {
        $input = ['Honda, Honda CRF, Honda CRF 450X'];

        $result = $this->sanitazer->sanitaze($input);

        $expectedOutput = [[
            'Honda -CRF -450X',
            'Honda CRF -450X',
            'Honda CRF 450X'
        ]];
        $this->assertEquals($expectedOutput, $result);
    }
}
