<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;


class TeamControllerTest extends PlayerControllerBaseTest
{
    public function test_sample()
    {
//        $requirements =
//            [
//                'position' => "defender",
//                'mainSkill' => "speed",
//                'numberOfPlayers' => 1
//            ];

        $requirements = [
            [
                'position' => "defender",
                'mainSkill' => "speed",
                'numberOfPlayers' => 1
            ],
//            [
//                'position' => "defender",
//                'mainSkill' => "defense",
//                'numberOfPlayers' => 1
//            ],
            [
                'position' => "midfielder",
                'mainSkill' => "speed",
                'numberOfPlayers' => 1
            ]
        ];


        $res = $this->postJson(self::REQ_TEAM_URI, ['data' => $requirements]);

        $this->assertNotNull($res);
    }
}
