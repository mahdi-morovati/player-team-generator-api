<?php

namespace App\Repositories\Player;

use App\Models\Player;
use App\Repositories\BaseRepository;

class PlayerRepository extends BaseRepository implements PlayerRepositoryInterface
{
    public function model(): string
    {
        return Player::class;
    }

    public function deletePlayerSkills(Player $player): int
    {
        return $player->playerSkills()->delete();
    }

}
