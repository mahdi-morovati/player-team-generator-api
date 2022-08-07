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

    public function getBestPlayerInPositionSkill(string $position, string $skill, int $numberOfPlayer)
    {
//        \DB::connection()->enableQueryLog();

        $players = $this->model->with('playerSkills')->where('position', $position)->whereHas('playerSkills', function ($query) use ($skill) {
            $query->where('skill', $skill);
        })
            ->with(['playerSkills' => function ($query) use ($skill) {
            $query->where('skill', $skill)->orderBy('value', 'desc');
        }])
            ->take($numberOfPlayer)->get();

//        $queries = \DB::getQueryLog();
//        dd($queries, __METHOD__);

        return $players;
    }

    public function getBestPlayerInPosition(string $position, int $numberOfPlayer)
    {
        return $this->model->with('playerSkills')->where('position', $position)->withMax('playerSkills', 'value')->orderBy('player_skills_max_value', 'desc')->take($numberOfPlayer)->get();
    }
}
