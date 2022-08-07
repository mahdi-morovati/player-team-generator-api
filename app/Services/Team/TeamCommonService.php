<?php

namespace App\Services\Team;

use App\Repositories\Player\PlayerRepositoryInterface;
use App\Services\CommonService;

class TeamCommonService extends CommonService
{
    public function __construct(public PlayerRepositoryInterface $repository)
    {
    }
}
