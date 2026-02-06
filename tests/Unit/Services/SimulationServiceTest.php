<?php

use App\Contracts\Repository\GameRepositoryInterface;
use App\Contracts\Repository\TeamRepositoryInterface;
use App\Models\Game;
use App\Models\Team;
use App\Services\League\SimulationService;
use App\ValueObjects\TeamPower;

beforeEach(function () {
    $this->gameRepository = Mockery::mock(GameRepositoryInterface::class);
    $this->teamRepository = Mockery::mock(TeamRepositoryInterface::class);

    $this->simulationService = new SimulationService(
        $this->gameRepository,
        $this->teamRepository
    );
});

test('it updates the match result via the game repository', function () {
    $gameId = 10;

    /**
     * DÜZELTME: stdClass yerine gerçek bir Model instance'ı oluşturuyoruz.
     * Database'e dokunmamak için 'make' (memory-only) kullanıyoruz.
     * ValueObject Cast'lerin modelde tanımlı olduğunu varsayıyoruz.
     */
    $homeTeam = new Team(['power' => new TeamPower(85)]);
    $awayTeam = new Team(['power' => new TeamPower(75)]);

    $mockGame = new Game([
        'id' => $gameId,
    ]);

    // İlişkileri (Relationship) manuel set ediyoruz (DB'ye gitmemesi için)
    $mockGame->setRelation('homeTeam', $homeTeam);
    $mockGame->setRelation('awayTeam', $awayTeam);

    // Repository beklentisi artık doğru tipte dönüyor
    $this->gameRepository->shouldReceive('find')
        ->once()
        ->with($gameId)
        ->andReturn($mockGame);

    $this->gameRepository->shouldReceive('updateMatchResult')
        ->once()
        ->with($gameId, Mockery::on(fn ($data) => isset($data['home_team_score']) && $data['is_played'] === true
        ))
        ->andReturn(true);

    $this->simulationService->playMatch($gameId);
});
