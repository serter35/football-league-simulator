<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company().' FC',
            'power' => $this->faker->numberBetween(45, 95),
            'points' => 0,
            'played' => 0,
            'won' => 0,
            'drawn' => 0,
            'lost' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_difference' => 0,
        ];
    }

    /**
     * Güçlü takım state'i (Testlerde varyasyon için)
     */
    public function elite(): static
    {
        return $this->state(fn (array $attributes) => [
            'power' => $this->faker->numberBetween(90, 100),
        ]);
    }
}
