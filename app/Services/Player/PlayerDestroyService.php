<?php

namespace App\Services\Player;


class PlayerDestroyService extends PlayerCommonService
{
    public function destroy($playerId)
    {
        $player = $this->repository->findOrFail($playerId);

        return \DB::transaction(function () use ($player) {
            $this->repository->deletePlayerSkills($player);
            $this->repository->delete($player);
        }, 3);
    }
}
