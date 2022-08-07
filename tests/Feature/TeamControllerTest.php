<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;


use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Support\Collection;

class TeamControllerTest extends PlayerControllerBaseTest
{
    public function test_sample()
    {
        $requirements = [
            [
                'position' => "defender",
                'mainSkill' => "speed",
                'numberOfPlayers' => 1
            ]
        ];

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => 'speed']))->create(['position' => 'defender']);
        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => 'attack']))->create(['position' => 'defender']);

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => 'speed']))->create(['position' => 'midfielder']);

        $res = $this->postJson(self::REQ_TEAM_URI, ['data' => $requirements]);

        $this->assertNotNull($res);

        $res->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'name',
                    'position',
                    'playerSkills' => [
                        '*' => [
                            'skill',
                            'value',
                        ]
                    ]
                ]
            ]);
        $this->assertInstanceOf(Collection::class, $res->getOriginalContent());
    }

    public function testInsufficientNumberOfPlayers()
    {
        $position = "defender";
        $requirements = [
            [
                'position' => $position,
                'mainSkill' => "speed",
                'numberOfPlayers' => 10
            ],
            [
                'position' => "midfielder",
                'mainSkill' => "speed",
                'numberOfPlayers' => 1
            ]
        ];

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => 'speed']))->create(['position' => 'defender']);

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => 'speed']))->create(['position' => 'midfielder']);

        $res = $this->postJson(self::REQ_TEAM_URI, ['data' => $requirements]);

        $this->assertNotNull($res);

        $res->assertNotFound()
            ->assertJson(['message' => __('messages.response.insufficient-number-of-players', ['position' => $position])]);
    }

}
