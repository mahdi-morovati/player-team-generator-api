<?php

namespace App\Services\Player;

use App\Repositories\BaseRepository;
use App\Repositories\Player\PlayerRepositoryInterface;
use App\Services\CommonService;

class PlayerCommonService extends CommonService
{
    public BaseRepository $repository;

    public function __construct(PlayerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}
