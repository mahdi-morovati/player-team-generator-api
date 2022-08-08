<?php

namespace App\Http\Controllers;

use App\Facades\ResponderFacade;
use App\Http\Requests\SelectTeamRequest;
use App\Http\Resources\TeamCollection;
use App\Services\Team\TeamProcessService;

class TeamController extends Controller
{
    public function process(SelectTeamRequest $request, TeamProcessService $teamProcessService)
    {
        $team = $teamProcessService->process($request->requirement);
        if ($team instanceof \Illuminate\Http\JsonResponse) return $team;
        return new TeamCollection($team);
    }

}
