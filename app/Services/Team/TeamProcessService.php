<?php

namespace App\Services\Team;


use App\Facades\ResponderFacade;
use Illuminate\Support\Collection;

class TeamProcessService extends TeamCommonService
{
    public function process(array $data)
    {
        $players = collect([]);
        foreach ($data as $item) {
            $tmp = [];
            $position = $item['position'];
            $skill = $item['mainSkill'];
            $numberOfPlayers = $item['numberOfPlayers'];
            $bestPlayerInPositionSkill = $this->repository->getBestPlayerInPositionSkill($position, $skill, $numberOfPlayers);
            $count = $bestPlayerInPositionSkill->count();
            if ($count <$numberOfPlayers) {
                $bestPlayerInPosition = $this->repository->getBestPlayerInPosition($position, $numberOfPlayers - $count);

                $tmp = $bestPlayerInPositionSkill->merge($bestPlayerInPosition);
            }
            if ($bestPlayerInPositionSkill->count() < $numberOfPlayers) {
                return ResponderFacade::notFound(__('messages.response.insufficient-number-of-players', ['position' => $position]));
            }
            $players = $players->merge($tmp);
        }
        return $players;
    }

}
