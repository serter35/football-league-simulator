<?php

namespace App\ValueObjects;

readonly class TeamPower extends BaseValueObject
{
    public function __construct(public int $value)
    {
        if ($value < 0 || $value > 100) {
            throw new \LogicException('Team power must be between 0 and 100.');
        }
    }
}
