<?php

namespace App\Services\League;

use App\Contracts\Repository\GameRepositoryInterface;
use App\Contracts\Repository\TeamRepositoryInterface;
use Illuminate\Support\Collection;
use Random\RandomException;

class PredictionService
{
    public function __construct(
        protected GameRepositoryInterface $gameRepository,
        protected TeamRepositoryInterface $teamRepository
    ) {}

    /**
     * @throws RandomException
     */
    public function calculatePredictions(): array
    {
        $teams = $this->teamRepository->all();
        $playedGames = $this->gameRepository->getPlayedGames();

        // 4. haftadan itibaren tahmin gösterilir.
        $currentWeek = $playedGames->pluck('week')->max() ?? 0;
        if ($currentWeek < 4) {
            return [];
        }

        $unplayedGames = $this->gameRepository->getUnplayedGames();
        $iterations = 1000;

        $teamIds = $teams->pluck('id')->toArray();
        $winCounts = array_fill_keys($teamIds, 0);

        for ($i = 0; $i < $iterations; $i++) {
            $standings = $this->getInitialStandingsForSimulation($teams);

            foreach ($unplayedGames as $game) {
                $homeTeam = $teams->firstWhere('id', $game->home_team_id);
                $awayTeam = $teams->firstWhere('id', $game->away_team_id);

                $result = $this->simulateImaginaryMatch($homeTeam, $awayTeam);
                $this->applyImaginaryResult($standings, $game->home_team_id, $game->away_team_id, $result);
            }

            $winnerId = $this->determineWinner($standings);
            $winCounts[$winnerId]++;
        }

        return $this->formatPredictionResults($winCounts, $iterations, $teams);
    }

    private function getInitialStandingsForSimulation(Collection $teams): array
    {
        $standings = [];
        foreach ($teams as $team) {
            $standings[$team->id] = [
                'points' => (int) $team->points->value,
                'gd' => (int) $team->goal_difference,
                'gf' => (int) $team->goals_for->value,
            ];
        }

        return $standings;
    }

    /**
     * @throws RandomException
     */
    private function simulateImaginaryMatch($home, $away): array
    {
        $homeScore = 0;
        $awayScore = 0;
        $homePower = $home->power->value;
        $awayPower = $away->power->value;

        for ($i = 0; $i < 6; $i++) {
            if (random_int(0, 100) < (($homePower + 5) / 3.5)) {
                $homeScore++;
            }
            if (random_int(0, 100) < ($awayPower / 3.5)) {
                $awayScore++;
            }
        }

        return ['home' => $homeScore, 'away' => $awayScore];
    }

    private function applyImaginaryResult(array &$standings, int $homeId, int $awayId, array $result): void
    {
        $standings[$homeId]['gf'] += $result['home'];
        $standings[$awayId]['gf'] += $result['away'];
        $standings[$homeId]['gd'] += ($result['home'] - $result['away']);
        $standings[$awayId]['gd'] += ($result['away'] - $result['home']);

        if ($result['home'] > $result['away']) {
            $standings[$homeId]['points'] += 3;
        } elseif ($result['home'] === $result['away']) {
            $standings[$homeId]['points'] += 1;
            $standings[$awayId]['points'] += 1;
        } else {
            $standings[$awayId]['points'] += 3;
        }
    }

    private function determineWinner(array $standings): int
    {
        uasort($standings, static function ($a, $b) {
            if ($a['points'] !== $b['points']) {
                return $b['points'] <=> $a['points'];
            }
            if ($a['gd'] !== $b['gd']) {
                return $b['gd'] <=> $a['gd'];
            }

            return $b['gf'] <=> $a['gf'];
        });

        return (int) array_key_first($standings);
    }

    private function formatPredictionResults(array $winCounts, int $iterations, Collection $teams): array
    {
        $formatted = [];
        foreach ($winCounts as $teamId => $count) {
            $team = $teams->firstWhere('id', $teamId);
            $formatted[] = [
                'team_id' => $teamId,
                'team_name' => $team->name,
                'percentage' => (float) number_format(($count / $iterations) * 100, 2),
            ];
        }

        usort($formatted, static fn ($a, $b) => $b['percentage'] <=> $a['percentage']);

        return $formatted;
    }
}
