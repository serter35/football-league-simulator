<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repository\GameRepositoryInterface;
use App\Models\Game;
use Illuminate\Support\Collection;

class GameRepository implements GameRepositoryInterface
{
    public function getPlayedGames(): Collection
    {
        return Game::where('is_played', true)->get();
    }

    public function getGamesByWeek(int $week): Collection
    {
        return Game::where('week', $week)->get();
    }

    public function getAllWithTeams(): Collection
    {
        return Game::with(['homeTeam', 'awayTeam'])->get();
    }

    public function getUnplayedGames(): Collection
    {
        return Game::where('is_played', false)->get();
    }

    public function find(int $gameId): ?Game
    {
        return Game::find($gameId);
    }

    public function fillAndInsert(array $data): bool
    {
        return Game::fillAndInsert($data);
    }

    public function updateMatchResult(int $id, array $results): bool
    {
        return Game::where('id', $id)->update($results);
    }

    public function truncate(): void
    {
        // Model üzerinden tüm kayıtları siler
        Game::truncate();
    }

    public function getCurrentWeek(): int
    {
        // Oynanmamış ilk maçın haftasını getir, eğer hepsi oynandıysa son haftayı getir.
        $nextGame = Game::where('is_played', false)
            ->orderBy('week', 'asc')
            ->first();

        return $nextGame->week ?? Game::max('week') ?? 1;
    }
}
