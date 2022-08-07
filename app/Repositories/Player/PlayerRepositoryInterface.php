<?php

namespace App\Repositories\Player;

interface PlayerRepositoryInterface
{
    public function getBestPlayerInPositionSkill(string $position, string $skill, int $numberOfPlayer);

    public function getBestPlayerInPosition(string $position, int $numberOfPlayer, string $skipSkill);
}
