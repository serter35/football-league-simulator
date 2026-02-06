<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repository\TeamRepositoryInterface;
use App\Models\Team;
use Illuminate\Support\Collection;

class TeamRepository implements TeamRepositoryInterface
{
    public function all(): Collection
    {
        return Team::latest('points')->latest('goal_difference')->get();
    }

    public function updateStats(int $teamId, array $stats): bool
    {
        return Team::where('id', $teamId)->update($stats);
    }

    public function resetAllStats(): bool
    {
        return Team::query()->update([
            'played' => 0,
            'won' => 0,
            'drawn' => 0,
            'lost' => 0,
            'points' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_difference' => 0,
        ]);
    }
}
