<?php

namespace App\Repositories\Player;

use App\Models\PlayerSkill;
use App\Repositories\BaseRepository;

class PlayerSkillRepository extends BaseRepository implements PlayerSkillRepositoryInterface
{
    public function model(): string
    {
        return PlayerSkill::class;
    }

}
