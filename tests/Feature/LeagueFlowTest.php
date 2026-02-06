<?php

use App\Models\Game;
use App\Models\Team;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // ValueObject cast'lerimizi de dolaylı yoldan test etmiş oluyoruz
    Team::factory()->create(['name' => 'Manchester City', 'power' => 90]);
    Team::factory()->create(['name' => 'Arsenal', 'power' => 85]);
    Team::factory()->create(['name' => 'Liverpool', 'power' => 88]);
    Team::factory()->create(['name' => 'Aston Villa', 'power' => 78]);
});

it('can generate a full fixture for 4 teams', function () {
    post(route('league.generate'))
        ->assertRedirect();

    // 4 takım, çift devre (Double Round Robin) = 12 maç
    assertDatabaseCount('games', 12);

    $firstWeekGames = Game::where('week', 1)->get();
    expect($firstWeekGames)->toHaveCount(2);
});

it('updates standings correctly after simulating a week', function () {
    post(route('league.generate'));

    // 1. haftayı simüle et
    post(route('league.simulate.week', ['week' => 1]))
        ->assertRedirect();

    // En az 2 maç oynanmış olmalı
    expect(Game::where('is_played', true)->count())->toBe(2);

    // Veritabanı seviyesinde kontrol (Primitive değerler üzerinden)
    assertDatabaseHas('teams', [
        'played' => 1,
    ]);

    // Nesne bazlı kontrol
    $sampleTeam = Team::first();

    /** * SIDE EFFECT FIX:
     * points artık GoalCount nesnesi. Karşılaştırma yaparken ->value
     * ya da equals() metodunu kullanmalıyız.
     */
    $expectedPoints = ($sampleTeam->won * 3) + $sampleTeam->drawn;

    expect($sampleTeam->points->value)->toBe($expectedPoints);
    expect($sampleTeam->played)->toBe(1);
});

it('enforces team power constraints through value objects during creation', function () {
    // 100'den büyük bir güç ile takım oluşturmaya çalışmak LogicException fırlatmalı
    expect(fn () => Team::factory()->create(['power' => 150]))
        ->toThrow(\LogicException::class, 'Team power must be between 0 and 100.');
});
