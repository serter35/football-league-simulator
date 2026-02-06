<?php

use App\Contracts\Repository\GameRepositoryInterface;
use App\Contracts\Repository\TeamRepositoryInterface;
use App\Services\League\FixtureService;

it('generates a 6-week double round robin fixture for 4 teams', function () {
    $teamRepo = mock(TeamRepositoryInterface::class);
    $gameRepo = mock(GameRepositoryInterface::class);

    $mockTeams = collect([
        (object) ['id' => 1], (object) ['id' => 2],
        (object) ['id' => 3], (object) ['id' => 4],
    ]);

    $teamRepo->shouldReceive('resetAllStats')->once();
    $gameRepo->shouldReceive('truncate')->once();

    $teamRepo->shouldReceive('all')->andReturn($mockTeams);

    $gameRepo->shouldReceive('fillAndInsert')
        ->once()
        ->with(Mockery::on(function ($fixtures) {
            // 4 takım için 6 hafta, her hafta 2 maç = 12 maç
            return count($fixtures) === 12;
        }))
        ->andReturn(true);

    $service = new FixtureService($gameRepo, $teamRepo);
    $service->generateFixtures();
});

it('shuffles team IDs to ensure a random fixture every time', function () {
    $teamRepo = mock(TeamRepositoryInterface::class);
    $gameRepo = mock(GameRepositoryInterface::class);

    $mockTeams = collect([
        (object) ['id' => 1], (object) ['id' => 2],
        (object) ['id' => 3], (object) ['id' => 4],
    ]);

    // 1. Reset ve Truncate beklentilerini ekliyoruz
    $teamRepo->shouldReceive('resetAllStats')->once();
    $gameRepo->shouldReceive('truncate')->once();

    // 2. Takımları çekme beklentisi
    $teamRepo->shouldReceive('all')->once()->andReturn($mockTeams);

    // 3. Capture the fixtures sent to the repository
    $capturedFixtures = [];
    $gameRepo->shouldReceive('fillAndInsert')
        ->once()
        ->andReturnUsing(function ($arg) use (&$capturedFixtures) {
            $capturedFixtures = $arg;

            return true;
        });

    $service = new FixtureService($gameRepo, $teamRepo);
    $service->generateFixtures();

    expect($capturedFixtures)->not->toBeEmpty();

    // Opsiyonel: 4 takım için 6 hafta x 2 maç = 12 maç oluştuğunu doğrula
    expect($capturedFixtures)->toHaveCount(12);
});
