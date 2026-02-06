<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'home_team_id' => Team::factory(),
            'away_team_id' => Team::factory(),
            'home_team_score' => null,
            'away_team_score' => null,
            'week' => $this->faker->numberBetween(1, 6),
            'is_played' => false,
        ];
    }

    /**
     * Oynanmış maç state'i
     */
    public function played(): static
    {
        return $this->state(fn (array $attributes) => [
            'home_team_score' => $this->faker->numberBetween(0, 5),
            'away_team_score' => $this->faker->numberBetween(0, 5),
            'is_played' => true,
        ]);
    }
}
