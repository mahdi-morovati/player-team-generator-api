<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Support\Collection;

class PlayerControllerListingTest extends PlayerControllerBaseTest
{
    public function test_sample()
    {
        $player = Player::factory()->has(PlayerSkill::factory()->count(2))->count(3)->create();

        $res = $this->get(self::REQ_URI);

        $this->assertNotNull($res);
        $res->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'position',
                        'playerSkills' => [
                            '*' => [
                                'id',
                                'skill',
                                'value',
                                'playerId',
                            ]
                        ]
                    ]
                ]
            ]);
        $this->assertInstanceOf(Collection::class, $res->getOriginalContent());
    }

}
