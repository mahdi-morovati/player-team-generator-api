<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW.
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Facades\ResponderFacade;
use App\Http\Requests\PlayerStoreRequest;
use App\Http\Resources\PlayerCollection;
use App\Http\Resources\PlayerResource;
use App\Services\Player\PlayerDestroyService;
use App\Services\Player\PlayerListService;
use App\Services\Player\PlayerStoreService;
use App\Services\Player\PlayerUpdateService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PlayerController extends Controller
{
    public function index(PlayerListService $playerListService)
    {
        $players = $playerListService->index();
        return new PlayerCollection($players);
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

        } catch (ModelNotFoundException $exception) {
            return ResponderFacade::notfound(__('messages.response.not-found'));
        } catch (\Exception $exception) {
            return ResponderFacade::internalError();
        }
    }

    public function destroy($playerId, PlayerDestroyService $playerDestroyService)
    {
        try {
            $playerDestroyService->destroy($playerId);
            return ResponderFacade::destroyed(__('messages.response.destroy'));

        } catch (ModelNotFoundException $exception) {
            return ResponderFacade::notfound(__('messages.response.not-found'));
        } catch (\Exception $exception) {
            return ResponderFacade::internalError();
        }
    }

}
