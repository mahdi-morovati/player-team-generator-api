<?php

namespace App\Services\Player;

use App\Repositories\Player\PlayerRepositoryInterface;
use App\Services\CommonService;

class PlayerCommonService extends CommonService
{
    public function __construct(public PlayerRepositoryInterface $repository)
    {
    }
}
