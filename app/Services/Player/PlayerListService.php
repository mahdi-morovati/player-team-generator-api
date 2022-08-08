<?php

namespace App\Services\Player;

use App\Services\CommonService;
use Illuminate\Http\Request;

class PlayerListService extends PlayerCommonService
{
    public function index()
    {
        return $this->repository->all(['playerSkills']);
    }
}
