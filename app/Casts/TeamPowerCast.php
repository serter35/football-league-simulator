<?php

namespace App\Casts;

use App\ValueObjects\TeamPower;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class TeamPowerCast implements CastsAttributes
{
    /**
     * Veritabanından gelen değeri ValueObject'e dönüştürür.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): TeamPower
    {
        return new TeamPower((int) $value);
    }

    /**
     * ValueObject'i veritabanına yazılacak formata dönüştürür.
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): int
    {
        if ($value instanceof TeamPower) {
            return $value->value;
        }

        return new TeamPower((int) $value)->value;
    }
}
