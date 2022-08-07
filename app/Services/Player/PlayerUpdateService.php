<?php

namespace App\Services\Player;



use App\Facades\ResponderFacade;
use App\Models\PlayerSkill;

class PlayerUpdateService extends PlayerCommonService
{
    public function update($playerId, string $name, string $position, array $playerSkills)
    {
        $player = $this->repository->find($playerId);
        if (is_null($player)) {
//            throw new \Exception('not found');
        }
        return \DB::transaction(function () use ($player, $name, $position, $playerSkills) {
            $this->repository->update($player, [
                'name' => $name,
                'position' => $position
            ]);

            $data =[];
            foreach ($playerSkills as $playerSkill) {
                $playerSkill['player_id'] = $player->id;
                $data[] = new PlayerSkill($playerSkill);
            }
            $player->playerSkills()->saveMany($data); // @todo fix it
            $player->load('playerSkills');
            return $player;
        }, 3);
    }
}
