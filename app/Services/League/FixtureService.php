<?php

namespace App\Services\League;

use App\Contracts\Repository\GameRepositoryInterface;
use App\Contracts\Repository\TeamRepositoryInterface;

class FixtureService
{
    public function __construct(
        protected GameRepositoryInterface $gameRepository,
        protected TeamRepositoryInterface $teamRepository
    ) {}

    /**
     * Lig fikstürünü oluşturur ve toplu şekilde kaydeder.
     */
    public function generateFixtures(): void
    {
        $this->teamRepository->resetAllStats();

        $this->gameRepository->truncate();

        $teams = $this->teamRepository->all();

        // Takım id'lerini alıyoruz (Örn: [1, 2, 3, 4])
        $teamIds = $teams->pluck('id')->shuffle()->toArray();
        $now = now();
        $fixtures = [];

        /** * 4 Takımlı Çift Devreli Lig Planı (6 Hafta)
         * Her hafta 2 maç oynanır.
         * Template: [Hafta => [[HomeIndex, AwayIndex], [HomeIndex, AwayIndex]]]
         */
        $scheduleTemplate = [
            1 => [[0, 1], [2, 3]],
            2 => [[0, 2], [1, 3]],
            3 => [[0, 3], [1, 2]],
            4 => [[1, 0], [3, 2]], // Rövanşlar başlıyor
            5 => [[2, 0], [3, 1]],
            6 => [[3, 0], [2, 1]],
        ];

        foreach ($scheduleTemplate as $week => $matchPairs) {
            foreach ($matchPairs as $pair) {
                $fixtures[] = [
                    'home_team_id' => $teamIds[$pair[0]],
                    'away_team_id' => $teamIds[$pair[1]],
                    'week' => $week,
                    'is_played' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Katmanlı mimari gereği: Veriyi hazırla ve Repository'e teslim et.
        $this->gameRepository->fillAndInsert($fixtures);
    }
}
