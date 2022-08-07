<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;


use App\Models\Player;
use App\Models\PlayerSkill;

class PlayerControllerUpdateTest extends PlayerControllerBaseTest
{
    public function test_sample()
    {
        Player::unguard();
        $data = [
            "name" => "test",
            "position" => "defender",
            "playerSkills" => [
                0 => [
                    "skill" => "attack",
                    "value" => 60
                ],
                1 => [
                    "skill" => "speed",
                    "value" => 80
                ]
            ]
        ];
        $player = Player::factory()->has(PlayerSkill::factory()->count(2))->state(['id' => 1])->create();

        $res = $this->putJson(self::REQ_URI . '1', $data);

        $this->assertNotNull($res);
        $res->assertOk()
            ->assertJsonStructure([
                'data' => [
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
            ]);
        $this->assertResponseContainsPlayer($res, $data);
    }
}
