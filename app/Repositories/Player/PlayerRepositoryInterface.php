<?php

namespace App\Repositories\Player;

interface PlayerRepositoryInterface
{
    public function create(string $name, string $position);
}
