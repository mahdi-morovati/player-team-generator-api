<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW.
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Http\Requests\PlayerStoreRequest;
use App\Http\Resources\PlayerResource;
use App\Services\Player\PlayerStoreService;
use App\Services\Player\PlayerUpdateService;

class PlayerController extends Controller
{
    public function index()
    {
        return response("Failed", 500);
    }

    public function show()
    {
        return response("Failed", 500);
    }

    public function store(PlayerStoreRequest $request, PlayerStoreService $playerStoreService)
    {
        $player = $playerStoreService->store($request->name, $request->position, $request->playerSkills);

        return new PlayerResource($player);
    }

    public function update($id, PlayerStoreRequest $request, PlayerUpdateService $playerUpdateService)
    {
        try {
            $player = $playerUpdateService->update($id, $request->name, $request->position, $request->playerSkills);

            return new PlayerResource($player);
        } catch (\Exception $exception) {
            dd(__METHOD__);
        }
    }

    public function destroy()
    {
        return response("Failed", 500);
    }
}
