<?php

namespace App\Services\League;

use App\Contracts\Repository\GameRepositoryInterface;
use App\Contracts\Repository\TeamRepositoryInterface;
use Illuminate\Support\Collection;
use Random\RandomException;

class SimulationService
{
    public function __construct(
        protected GameRepositoryInterface $gameRepository,
        protected TeamRepositoryInterface $teamRepository
    ) {}

    /**
     * Tüm ligi tek seferde simüle eder.
     * @throws RandomException
     */
    public function simulateAllWeeks(): void
    {
        for ($week = 1; $week <= 6; $week++) {
            $this->simulateWeek($week);
        }
    }

    /**
     * Belirli bir haftayı simüle eder.
     * @throws RandomException
     */
    public function simulateWeek(int $week): void
    {
        $games = $this->gameRepository->getGamesByWeek($week);

        foreach ($games as $game) {
            if ($game->is_played) {
                continue;
            }

            $this->playMatch($game->id);
        }

        $this->refreshStandings();
    }

    /**
     * Tek bir maçı oynatır ve repository üzerinden kaydeder.
     * @throws RandomException
     */
    public function playMatch(int $gameId): void
    {
        $game = $this->gameRepository->find($gameId);

        if (! $game) {
            return;
        }

        /** * SIDE EFFECT FIX: $game->homeTeam->power artık bir TeamPower nesnesi.
         * Matematiksel işlem yapabilmek için ->value üzerinden erişiyoruz.
         */
        $homePowerValue = $game->homeTeam->power->value;
        $awayPowerValue = $game->awayTeam->power->value;

        $homeScore = $this->calculateGoals($homePowerValue + 5);
        $awayScore = $this->calculateGoals($awayPowerValue);

        $this->gameRepository->updateMatchResult($gameId, [
            'home_team_score' => $homeScore,
            'away_team_score' => $awayScore,
            'is_played' => true,
            'updated_at' => now(),
        ]);
    }

    /**
     * Puan durumunu tüm oynanmış maçlara göre sıfırdan hesaplar.
     */
    public function refreshStandings(): void
    {
        $teams = $this->teamRepository->all();
        $allPlayedGames = $this->gameRepository->getPlayedGames();

        foreach ($teams as $team) {
            $stats = $this->calculateTeamStats($team->id, $allPlayedGames);
            $this->teamRepository->updateStats($team->id, $stats);
        }
    }

    /**
     * Gol hesaplama mantığı (Power bazlı olasılık).
     * @throws RandomException
     */
    private function calculateGoals(int $power): int
    {
        $goals = 0;
        for ($i = 0; $i < 6; $i++) {
            if (random_int(0, 100) < ($power / 3.5)) {
                $goals++;
            }
        }

        return $goals;
    }

    /**
     * Takım istatistiklerini hesaplayan private yardımcı metot.
     */
    private function calculateTeamStats(int $teamId, Collection $games): array
    {
        $stats = [
            'points' => 0, 'played' => 0, 'won' => 0, 'drawn' => 0,
            'lost' => 0, 'goals_for' => 0, 'goals_against' => 0, 'goal_difference' => 0,
        ];

        foreach ($games as $game) {
            $isHome = $game->home_team_id === $teamId;
            $isAway = $game->away_team_id === $teamId;

            if (! $isHome && ! $isAway) {
                continue;
            }

            /**
             * SIDE EFFECT FIX: Skorlar artık GoalCount nesnesi olarak geliyor.
             * Karşılaştırma ve toplama işlemlerinde ->value kullanıyoruz.
             */
            $scored = $isHome ? $game->home_team_score->value : $game->away_team_score->value;
            $conceded = $isHome ? $game->away_team_score->value : $game->home_team_score->value;

            $stats['played']++;
            $stats['goals_for'] += $scored;
            $stats['goals_against'] += $conceded;

            if ($scored > $conceded) {
                $stats['won']++;
                $stats['points'] += 3;
            } elseif ($scored === $conceded) {
                $stats['drawn']++;
                $stats['points']++;
            } else {
                $stats['lost']++;
            }
        }

        // goal_difference için Cast yazmadık, doğrudan integer hesaplıyoruz.
        $stats['goal_difference'] = $stats['goals_for'] - $stats['goals_against'];

        return $stats;
    }
}
