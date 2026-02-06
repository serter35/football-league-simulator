<?php

namespace App\Http\Controllers;

use App\Contracts\Repository\GameRepositoryInterface;
use App\Contracts\Repository\TeamRepositoryInterface;
use App\Http\Resources\GameResource;
use App\Http\Resources\TeamResource;
use App\Services\League\FixtureService;
use App\Services\League\PredictionService;
use App\Services\League\SimulationService;
use Inertia\Inertia;
use Inertia\Response;
use Random\RandomException;

class LeagueController extends Controller
{
    public function __construct(
        protected FixtureService $fixtureService,
        protected SimulationService $simulationService,
        protected PredictionService $predictionService,
        protected TeamRepositoryInterface $teamRepository,
        protected GameRepositoryInterface $gameRepository
    ) {}

    public function index(): Response
    {
        return Inertia::render('League/Dashboard', [
            'teams' => TeamResource::collection($this->teamRepository->all()),

            'fixtures' => Inertia::defer(fn () => GameResource::collection($this->gameRepository->getAllWithTeams())),

            'predictions' => Inertia::defer(fn () => $this->predictionService->calculatePredictions()),

            'total_weeks' => 6,
        ]);
    }

    public function generate()
    {
        $this->fixtureService->generateFixtures();

        return back();
    }

    /**
     * @throws RandomException
     */
    public function simulateNextWeek(int $week)
    {
        $this->simulationService->simulateWeek($week);

        return back();
    }

    /**
     * @throws RandomException
     */
    public function simulateAll()
    {
        $this->simulationService->simulateAllWeeks();

        return back();
    }
}
