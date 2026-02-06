<?php

namespace App\Casts;

use App\ValueObjects\GoalCount;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class GoalCountCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): GoalCount
    {
        return new GoalCount($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        if ($value instanceof GoalCount) {
            return $value->value;
        }

        return new GoalCount($value)->value;
    }
}
