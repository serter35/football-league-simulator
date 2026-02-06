<?php

namespace App\ValueObjects;

abstract readonly class BaseValueObject implements \Stringable
{
    public function equals(self $other): bool
    {
        return get_class($this) === get_class($other) && $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
