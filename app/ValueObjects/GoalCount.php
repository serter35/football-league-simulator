<?php

namespace App\ValueObjects;

readonly class GoalCount extends BaseValueObject
{
    public function __construct(public ?int $value)
    {
        if (is_numeric($this->value) && $value < 0) {
            throw new \LogicException('Goal count cannot be negative.');
        }
    }
}
