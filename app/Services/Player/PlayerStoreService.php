<?php

namespace App\Services\Player;


class PlayerStoreService extends PlayerCommonService
{
    public function store(string $name, string $position, array $playerSkills)
    {

        return \DB::transaction(function () use ($name, $position, $playerSkills) {
            $player = $this->repository->store([
                'name' => $name,
                'position' => $position
            ]);

            $data = [];
            foreach ($playerSkills as $playerSkill) {
                $playerSkill['player_id'] = $player->id;
                $data[] = $playerSkill;
            }
            $player->playerSkills()->createMany($data);
            $player->load('playerSkills');
            return $player;
        }, 3);
    }
}
