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


    public function create(string $name, string $position)
    {
        // TODO: Implement create() method.
    }
}
