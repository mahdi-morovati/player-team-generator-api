<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW.
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Http\Requests\PlayerStoreRequest;
use App\Http\Resources\PlayerResource;
use App\Services\Player\PlayerStoreService;

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
        dump($request->playerSkills, '=========================');

        $player = $playerStoreService->store($request->name, $request->position, $request->playerSkills);

        return new PlayerResource($player);
    }

    public function update()
    {
        return response("Failed", 500);
    }

    public function destroy()
    {
        return response("Failed", 500);
    }
}
