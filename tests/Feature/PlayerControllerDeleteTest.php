<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use App\Models\Player;
use App\Models\PlayerSkill;

class PlayerControllerDeleteTest extends PlayerControllerBaseTest
{

    const BEARER_TOKEN = 'Bearer SkFabTZibXE1aE14ckpQUUxHc2dnQ2RzdlFRTTM2NFE2cGI4d3RQNjZmdEFITmdBQkE=';

    public function test_sample()
    {
        Player::unguard();

        $player = Player::factory()->has(PlayerSkill::factory()->count(2))->state(['id' => 1])->create();

        $playerSkills = $player->playerSkills()->first();

        $res = $this->delete(self::REQ_URI . '1', [], ['Authorization'=> self::BEARER_TOKEN]);

        $this->assertNotNull($res);
        $res->assertOk()
            ->assertJson(['message' => __('messages.response.destroy')]);

        $this->assertDatabaseMissing(Player::class, $player->toArray());
        $this->assertDatabaseMissing(PlayerSkill::class, $playerSkills->toArray());
    }

    public function testNotFound()
    {
        $res = $this->delete(self::REQ_URI . '1121212', [], ['Authorization'=> self::BEARER_TOKEN]);

        $this->assertNotNull($res);
        $res->assertNotFound()
            ->assertJson(['message' => __('messages.response.not-found')]);
    }

    public function testNotFoundLoggedIn()
    {
        $res = $this->delete(self::REQ_URI . '1121212', [], ['Authorization'=> '']);

        $this->assertNotNull($res);
        $res->assertUnauthorized()
            ->assertJson(['message' => __('messages.response.unauthorizedError')]);
    }
}
