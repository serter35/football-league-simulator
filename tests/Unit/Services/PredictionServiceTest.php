<?php

use App\Contracts\Repository\GameRepositoryInterface;
use App\Contracts\Repository\TeamRepositoryInterface;
use App\Services\League\PredictionService;
use App\ValueObjects\GoalCount;
use App\ValueObjects\TeamPower;

it('returns an empty array if current week is less than 4', function () {
    // Mock hazırlığı
    $gameRepo = mock(GameRepositoryInterface::class);
    $teamRepo = mock(TeamRepositoryInterface::class);

    // Oynanmış maçlar 3. haftada kalsın
    $playedGames = collect([
        (object) ['week' => 1],
        (object) ['week' => 2],
        (object) ['week' => 3],
    ]);

    $teamRepo->shouldReceive('all')->once()->andReturn(collect());
    $gameRepo->shouldReceive('getPlayedGames')->once()->andReturn($playedGames);

    $service = new PredictionService($gameRepo, $teamRepo);

    expect($service->calculatePredictions())->toBeArray()->toBeEmpty();
});

it('calculates predictions correctly using value objects after week 4', function () {
    $gameRepo = mock(GameRepositoryInterface::class);
    $teamRepo = mock(TeamRepositoryInterface::class);

    // 1. Mock Veriler: 4. haftaya ulaştık
    $playedGames = collect([(object) ['week' => 4]]);

    // Takımlar (ValueObject cast'lerini simüle ediyoruz)
    $mockTeams = collect([
        (object) [
            'id' => 1,
            'name' => 'Team A',
            'power' => new TeamPower(90),
            'points' => new GoalCount(10),
            'goal_difference' => 5,
            'goals_for' => new GoalCount(15),
        ],
        (object) [
            'id' => 2,
            'name' => 'Team B',
            'power' => new TeamPower(50),
            'points' => new GoalCount(2),
            'goal_difference' => -3,
            'goals_for' => new GoalCount(5),
        ],
    ]);

    // Oynanmamış 1 maç kalsın
    $unplayedGames = collect([
        (object) ['home_team_id' => 1, 'away_team_id' => 2, 'week' => 5],
    ]);

    // Repository Beklentileri
    $teamRepo->shouldReceive('all')->andReturn($mockTeams);
    $gameRepo->shouldReceive('getPlayedGames')->andReturn($playedGames);
    $gameRepo->shouldReceive('getUnplayedGames')->andReturn($unplayedGames);

    $service = new PredictionService($gameRepo, $teamRepo);
    $results = $service->calculatePredictions();

    // Sonuç Kontrolleri
    expect($results)->not->toBeEmpty()
        ->and($results)->toBeArray()
        ->and($results[0])->toHaveKeys(['team_id', 'team_name', 'percentage']);

    // Team A'nın gücü çok yüksek olduğu için kazanma ihtimali yüksek çıkmalı (Matematiksel tutarlılık)
    $teamA = collect($results)->firstWhere('team_id', 1);
    $teamB = collect($results)->firstWhere('team_id', 2);

    expect($teamA['percentage'])->toBeGreaterThan($teamB['percentage']);
});

it('correctly extracts primitive values from value objects in simulation', function () {
    /**
     * Bu test aslında getInitialStandingsForSimulation metodunun
     * iç mantığını dolaylı yoldan denetler.
     * Eğer ->value çağrısı hata verirse test patlayacaktır.
     */
    $gameRepo = mock(GameRepositoryInterface::class);
    $teamRepo = mock(TeamRepositoryInterface::class);

    $mockTeam = (object) [
        'id' => 1,
        'name' => 'Test',
        'power' => new TeamPower(100),
        'points' => new GoalCount(0),
        'goal_difference' => 0,
        'goals_for' => new GoalCount(0),
    ];

    $teamRepo->shouldReceive('all')->andReturn(collect([$mockTeam]));
    $gameRepo->shouldReceive('getPlayedGames')->andReturn(collect([(object) ['week' => 5]]));
    $gameRepo->shouldReceive('getUnplayedGames')->andReturn(collect());

    $service = new PredictionService($gameRepo, $teamRepo);

    // Hata fırlatmadan çalışmalı
    expect(fn () => $service->calculatePredictions())->not->toThrow(Throwable::class);
});
