<?php

namespace App\Services\Player;



use App\Facades\ResponderFacade;
use App\Models\PlayerSkill;

class PlayerUpdateService extends PlayerCommonService
{
    public function update($playerId, string $name, string $position, array $playerSkills)
    {
        $player = $this->repository->findOrFail($playerId);

        return \DB::transaction(function () use ($player, $name, $position, $playerSkills) {
            $player = $this->repository->update($player, [
                'name' => $name,
                'position' => $position
            ]);

            $player->playerSkills()->delete();

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
