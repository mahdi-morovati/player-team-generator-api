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
            ]);
        $this->assertResponseContainsPlayer($res, $data);
    }

    /**
     * @dataProvider validationDataProvider
     */
    public function testValidation(array $invalidData, string $invalidParameter)
    {
        $validData = [
            'name' => 'player 1',
            'position' => 'defender',
            'playerSkills' => [
                0 => [
                    'skill' => 'attack',
                    'value' => 60
                ],
                1 => [
                    'skill' => 'speed',
                    'value' => 80
                ]
            ]
        ];

        $data = array_merge($validData, $invalidData);
        $res = $this->postJson(self::REQ_URI, $data);

        $res->assertStatus(422);

        $res->assertJsonValidationErrors([$invalidParameter]);
    }

    /**
     * @dataProvider skillValidationDataProvider
     */
    public function testValidationPlayerSkills(array $invalidData, string $invalidParameter)
    {
        $validData = [
            'name' => 'player 1',
            'position' => 'defender',
            'playerSkills' => [
                0 => [
                    'skill' => 'attack',
                    'value' => 60
                ],
                1 => [
                    'skill' => 'speed',
                    'value' => 80
                ]
            ]
        ];

        $data = array_merge($validData, $invalidData);
        $res = $this->postJson(self::REQ_URI, $data);

        $res->assertStatus(422);

    }

    private function validationDataProvider(): array
    {
        return [
            [['name' => null], 'name'],
            [['name' => ''], 'name'],
            [['name' => 12312321], 'name'],
            [['name' => []], 'name'],
            [['name' => [673890]], 'name'],
            [['name' => ['978832830234']], 'name'],
            [['name' => ['FCKGWRHQQ2']], 'name'],

            [['position' => null], 'position'],
            [['position' => ''], 'position'],
            [['position' => 12312321], 'position'],
            [['position' => []], 'position'],
            [['position' => [673890]], 'position'],
            [['position' => ['978832830234']], 'position'],
            [['position' => ['FCKGWRHQQ2']], 'position'],

        ];
    }

    private function skillValidationDataProvider(): array
    {
        return [
            [['playerSkills' => null], 'playerSkills'],
            [['playerSkills' => ''], 'playerSkills'],
            [['playerSkills' => 12312321], 'playerSkills'],
            [['playerSkills' => []], 'playerSkills'],
            [['playerSkills' => [673890]], 'playerSkills'],
            [['playerSkills' => ['978832830234']], 'playerSkills'],
            [[
                'playerSkills' => [
                    0 => [
                        'skill' => null,
                        'value' => 60
                    ]
                ]
            ], 'playerSkills'],
            [[
                'playerSkills' => [
                    0 => [
                        'value' => 60
                    ]
                ]
            ], 'playerSkills'],
            [[
                'playerSkills' => [
                    0 => [
                        'skill' => 'wrong-skill',
                        'value' => 60
                    ]
                ]
            ], 'playerSkills'],
            [[
                'playerSkills' => [
                    0 => [
                        'skill' => 'attack',
                        'value' => 'false-value'
                    ]
                ]
            ], 'playerSkills'],

        ];
    }

}
