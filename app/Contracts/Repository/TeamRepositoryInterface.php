<?php

namespace App\Contracts\Repository;

use Illuminate\Support\Collection;

interface TeamRepositoryInterface
{
    public function all(): Collection;

    public function updateStats(int $teamId, array $stats): bool;

    public function resetAllStats(): bool;
}
