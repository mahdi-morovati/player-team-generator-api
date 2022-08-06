<?php


// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;


use Illuminate\Testing\TestResponse;

class PlayerControllerCreateTest extends PlayerControllerBaseTest
{
    public function testHappy()
    {
        $this->withoutExceptionHandling();
        $data = [
            'name' => 'test',
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

        $res = $this->postJson(self::REQ_URI, $data);

        $this->assertNotNull($res);
        $res->assertCreated()
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

    private function assertResponseContainsPlayer(TestResponse $response, array $data): void
    {
        $response->assertJson([
            'data' => [
                'name' => $data['name'],
                'position' => $data['position'],
                'playerSkills' => collect($data['playerSkills'])->map(function ($array) {
                    return [
                        'skill' => $array['skill'],
                        'value' => $array['value'],
                    ];
                })->toArray()
            ]
        ]);
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
                        'skillw' => 'attack',
                        'valuew' => 60
                    ],
                    1 => [
                        'skillw' => 'speed',
                        'valuew' => 80
                    ]
                ]
            ], 'playerSkills'],

        ];
    }

    public function _testInvalidPosition()
    {
        $data = [
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
        $res = $this->postJson(self::REQ_URI, $data);
        $res->assertStatus(422)
            ->assertJsonPath('message', 'The name field is required.');
    }

}
