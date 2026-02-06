<?php

namespace App\Contracts\Repository;

use App\Models\Game;
use Illuminate\Support\Collection;

interface GameRepositoryInterface
{
    public function find(int $gameId): ?Game;

    public function getGamesByWeek(int $week): Collection;

    public function getPlayedGames(): Collection;

    public function updateMatchResult(int $gameId, array $data): bool;

    public function fillAndInsert(array $data): bool;

    public function truncate(): void;

    public function getCurrentWeek(): int;
}
