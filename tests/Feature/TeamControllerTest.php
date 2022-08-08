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

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => Player::POSITION_DEFENDER]);
        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_ATTACK]))->create(['position' => Player::POSITION_DEFENDER]);

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => 'midfielder']);

        $res = $this->postJson(self::REQ_TEAM_URI, ['requirement' => $requirements]);

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

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => Player::POSITION_DEFENDER]);

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => 'midfielder']);

        $res = $this->postJson(self::REQ_TEAM_URI, ['requirement' => $requirements]);

        $this->assertNotNull($res);

        $res->assertNotFound()
            ->assertJson(['message' => __('messages.response.insufficient-number-of-players', ['position' => $position])]);
    }

    /**
     * @dataProvider validationDataProvider
     */
    public function testValidation(array $invalidData, string $invalidParameter)
    {
        $position = "defender";
        $requirements = [
            [
                'position' => $position,
                'mainSkill' => "speed",
                'numberOfPlayers' => 1
            ]
        ];

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => Player::POSITION_DEFENDER]);

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => 'midfielder']);
        $data = array_merge($requirements, $invalidData);
        $res = $this->postJson(self::REQ_TEAM_URI, ['requirement' => $data]);

        $res->assertStatus(422);

    }

    private function validationDataProvider(): array
    {
        return [
            [[null], 'requirement'],
            [[0 => ''], 'requirement'],
            [[0 => []], 'requirement'],
            [[0 => ['position' => Player::POSITION_DEFENDER], 1 => ['position' => Player::POSITION_DEFENDER]], 'requirement'],
            [[0 => ['position' => Player::POSITION_DEFENDER, 'mainSkill' => null]], 'requirement'],
            [[0 => ['position' => Player::POSITION_DEFENDER, 'mainSkill' => 234234]], 'requirement'],
            [[0 => ['position' => Player::POSITION_DEFENDER, 'mainSkill' => PlayerSkill::SKILL_SPEED, 'numberOfPlayers' => null]], 'requirement'],
            [[0 => ['position' => Player::POSITION_DEFENDER, 'mainSkill' => PlayerSkill::SKILL_SPEED, 'numberOfPlayers' => '']], 'requirement'],
            [[0 => ['position' => Player::POSITION_DEFENDER, 'mainSkill' => PlayerSkill::SKILL_SPEED, 'numberOfPlayers' => 'qwrewrew']], 'requirement'],

        ];
    }

    public function testBestPlayerWithRequiredSkills()
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

        Player::factory()->has(PlayerSkill::factory()->state(['skill' => PlayerSkill::SKILL_SPEED, 'value' => $skillBestValue]))->create(['position' => Player::POSITION_DEFENDER]);
        Player::factory()->has(PlayerSkill::factory()->state(['skill' => PlayerSkill::SKILL_SPEED, 'value' => 5]))->create(['position' => Player::POSITION_DEFENDER]);

        $res = $this->postJson(self::REQ_TEAM_URI, ['requirement' => $requirements]);

        $this->assertNotNull($res);

        $res->assertok();
        $this->assertEquals($skillBestValue, $res->offsetGet(0)['playerSkills'][0]['value']);
        $this->assertEquals(5, $res->offsetGet(1)['playerSkills'][0]['value']);
    }

    public function testBestPlayerWithPositionInsufficientSkillPriorityWithRequiredSkill()
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

        Player::factory()->has(PlayerSkill::factory()->state(['skill' => PlayerSkill::SKILL_SPEED, 'value' => $skillBestValue]))->create(['position' => Player::POSITION_DEFENDER]);
        Player::factory()->has(PlayerSkill::factory()->state(['skill' => PlayerSkill::SKILL_ATTACK, 'value' => 20]))->create(['position' => Player::POSITION_DEFENDER]);

        $res = $this->postJson(self::REQ_TEAM_URI, ['requirement' => $requirements]);

        $this->assertNotNull($res);

        $res->assertok();
        $this->assertEquals($skillBestValue, $res->offsetGet(0)['playerSkills'][0]['value']);
        $this->assertEquals(20, $res->offsetGet(1)['playerSkills'][0]['value']);
    }

    public function testBestPlayerWithPositionWithoutRequiredSkill()
    {
        $position = "defender";
        $requirements = [
            [
                'position' => $position,
                'mainSkill' => "speed",
                'numberOfPlayers' => 2
            ]
        ];

        Player::factory()->has(PlayerSkill::factory()->state(['skill' => PlayerSkill::SKILL_STAMINA, 'value' => 10]))->create(['position' => Player::POSITION_DEFENDER]);
        Player::factory()->has(PlayerSkill::factory()->state(['skill' => PlayerSkill::SKILL_ATTACK, 'value' => 20]))->create(['position' => Player::POSITION_DEFENDER]);

        $res = $this->postJson(self::REQ_TEAM_URI, ['requirement' => $requirements]);

        $this->assertNotNull($res);

        $res->assertok();
        $this->assertEquals(20, $res->offsetGet(0)['playerSkills'][0]['value']);
        $this->assertEquals(10, $res->offsetGet(1)['playerSkills'][0]['value']);
    }

    public function testBestPlayerWithPositionWithoutRequiredSkillWithOtherSkills()
    {
        $position = "defender";
        $requirements = [
            [
                'position' => $position,
                'mainSkill' => "speed",
                'numberOfPlayers' => 2
            ]
        ];


        $player1 = Player::factory()->create(['position' => Player::POSITION_DEFENDER]);
        $player1->playerSkills()->createMany([
            ['skill' => PlayerSkill::SKILL_STAMINA, 'value' => 10],
            ['skill' => 'strength', 'value' => 12],
        ]);
        Player::factory()->has(PlayerSkill::factory()->state(['skill' => PlayerSkill::SKILL_ATTACK, 'value' => 20]))->create(['position' => Player::POSITION_DEFENDER]);
        Player::factory()->has(PlayerSkill::factory()->state(['skill' => PlayerSkill::SKILL_DEFENSE, 'value' => 23]))->create(['position' => Player::POSITION_DEFENDER]);

        $res = $this->postJson(self::REQ_TEAM_URI, ['requirement' => $requirements]);

        $this->assertNotNull($res);

        $res->assertok();
        $this->assertEquals(23, $res->offsetGet(0)['playerSkills'][0]['value']);
        $this->assertEquals(20, $res->offsetGet(1)['playerSkills'][0]['value']);
    }

    public function testValidationMessagePositionIsRequired()
    {
        $requirements = [['position-wrong' => Player::POSITION_DEFENDER]];

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => Player::POSITION_DEFENDER]);

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => 'midfielder']);
        $res = $this->postJson(self::REQ_TEAM_URI, ['requirement' => $requirements]);

        $res->assertStatus(422)
            ->assertSee('is required');

    }

    public function testValidationMessagePositionMustBeString()
    {
        $requirements = [['position' => 123123]];

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => Player::POSITION_DEFENDER]);

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => 'midfielder']);
        $res = $this->postJson(self::REQ_TEAM_URI, ['requirement' => $requirements]);

        $res->assertStatus(422)
            ->assertSee('must be string');

    }

    public function testValidationMessageNumberOfPlayersMustBeInteger()
    {
        $requirements = [['position' => Player::POSITION_DEFENDER, 'mailSkill' => PlayerSkill::SKILL_STAMINA, 'numberOfPlayers' => 'sdfsdf']];

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => Player::POSITION_DEFENDER]);

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => 'midfielder']);
        $res = $this->postJson(self::REQ_TEAM_URI, ['requirement' => $requirements]);

        $res->assertStatus(422)
            ->assertSee('must be integer');

    }

    public function testValidationMessagePositionMustBeUnique()
    {
        $requirements = [
            ['position' => Player::POSITION_DEFENDER],
            ['position' => Player::POSITION_DEFENDER]
        ];

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => Player::POSITION_DEFENDER]);

        Player::factory()->has(PlayerSkill::factory()->count(2)->state(['skill' => PlayerSkill::SKILL_SPEED]))->create(['position' => 'midfielder']);
        $res = $this->postJson(self::REQ_TEAM_URI, ['requirement' => $requirements]);

        $res->assertStatus(422)
            ->assertSee('must be distinct');

    }

}
