<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

abstract class PlayerControllerBaseTest extends TestCase
{
    use RefreshDatabase;

    final const REQ_URI = '/api/player/';
    final const REQ_TEAM_URI = '/api/team/process';


    protected function log($data)
    {
        fwrite(STDERR, print_r($data, TRUE));
    }

    protected function assertResponseContainsPlayer(TestResponse $response, array $data): void
    {
        $response->assertJson([
            'name' => $data['name'],
            'position' => $data['position'],
            'playerSkills' => collect($data['playerSkills'])->map(function ($array) {
                return [
                    'skill' => $array['skill'],
                    'value' => $array['value'],
                ];
            })->toArray()
        ]);
    }
}
