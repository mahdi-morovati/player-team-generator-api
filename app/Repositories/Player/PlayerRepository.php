<?php

namespace App\Repositories\Player;

use App\Models\Player;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

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

    public function getBestPlayerInPositionSkill(string $position, string $skill, int $numberOfPlayer): Collection|array
    {
        return $this->model->with('playerSkills')->where('position', $position)->whereHas('playerSkills', function ($query) use ($skill) {
            $query->where('skill', $skill);
        })
            ->with(['playerSkills' => function ($query) use ($skill) {
            $query->where('skill', $skill)->orderBy('value', 'desc');
        }])
            ->take($numberOfPlayer)->get();
    }

    public function getBestPlayerInPosition(string $position, int $numberOfPlayer): Collection|array
    {
        return $this->model->with('playerSkills')->where('position', $position)->withMax('playerSkills', 'value')->orderBy('player_skills_max_value', 'desc')->take($numberOfPlayer)->get();
    }
}
