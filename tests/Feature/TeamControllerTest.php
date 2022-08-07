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

    public function testTrueBestPlayerWithTheSkill()
    {
        $position = "defender";
        $skillBestValue = 10;
        $requirements = [
            [
                'position' => $position,
                'mainSkill' => "speed",
                'numberOfPlayers' => 2
            ]
        ];

        Player::factory()->has(PlayerSkill::factory()->state(['skill' => 'speed', 'value' => $skillBestValue]))->create(['position' => 'defender']);
        Player::factory()->has(PlayerSkill::factory()->state(['skill' => 'speed', 'value' => 5]))->create(['position' => 'defender']);

        $res = $this->postJson(self::REQ_TEAM_URI, ['data' => $requirements]);

        $this->assertNotNull($res);

        $res->assertok();
        $this->assertEquals($skillBestValue, $res->offsetGet(0)['playerSkills'][0]['value']);
        $this->assertEquals(5, $res->offsetGet(1)['playerSkills'][0]['value']);
    }

    public function testTrueBestPlayerWithThePositionWithoutSkill()
    {
        $position = "defender";
        $requirements = [
            [
                'position' => $position,
                'mainSkill' => "speed",
                'numberOfPlayers' => 1
            ]
        ];

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => 'attack', 'value' => 10]))->create(['position' => 'defender']);


        $res = $this->postJson(self::REQ_TEAM_URI, ['data' => $requirements]);

        $this->assertNotNull($res);

        $res->assertNotFound()
            ->assertJson(['message' => __('messages.response.insufficient-number-of-players', ['position' => $position])]);
    }

}
