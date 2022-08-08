<?php

namespace App\Services\Team;


use App\Facades\ResponderFacade;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class TeamProcessService extends TeamCommonService
{
    public function process(array $data): Collection | JsonResponse
    {
        $bestPlayerInPositionSkill = collect([]);

        foreach ($data as $item) {
            $position = $item['position'];
            $skill = $item['mainSkill'];
            $numberOfPlayers = $item['numberOfPlayers'];
            $tmp = $this->repository->getBestPlayerInPositionSkill($position, $skill, $numberOfPlayers);
            $bestPlayerInPositionSkill = $bestPlayerInPositionSkill->merge($tmp);
            $count = $bestPlayerInPositionSkill->count();
            if ($count < $numberOfPlayers) {
                $bestPlayerInPosition = $this->repository->getBestPlayerInPosition($position, $numberOfPlayers - $count, $skill);

                $bestPlayerInPositionSkill = $bestPlayerInPositionSkill->merge($bestPlayerInPosition);
            }
            if ($bestPlayerInPositionSkill->count() < $numberOfPlayers) {
                return ResponderFacade::notFound(__('messages.response.insufficient-number-of-players', ['position' => $position]));
            }
        }
        return $bestPlayerInPositionSkill;
    }

}
