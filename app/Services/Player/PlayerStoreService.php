<?php

namespace App\Services\Player;

use App\Services\CommonService;
use Illuminate\Http\Request;

class PlayerStoreService extends PlayerCommonService
{
    public function                 store(string $name, string $position, array $playerSkills)
    {

        return \DB::transaction(function () use ($name, $position, $playerSkills) {
            $player = $this->repository->store([
                'name' => $name,
                'position' => $position
            ]);

            $data =[];
            foreach ($playerSkills as $playerSkill) {
                $playerSkill['player_id'] = $player->id;
                $data[] = $playerSkill;
            }
            $player->playerSkills()->insert($data);
            $player->load('playerSkills');
            return $player;
        }, 3);
    }
}
