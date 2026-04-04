<?php

declare(strict_types=1);

namespace App\Advertise\dto;

final readonly class UserInputDto
{
    /**
     * @param string[] $firstLine
     * @param string[] $secondLine
     * @param string[] $thirdLine
     */
    public function __construct(
        public array $firstLine,
        public array $secondLine,
        public array $thirdLine,
    ) {}
}
