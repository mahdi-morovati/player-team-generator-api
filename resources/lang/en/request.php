<?php

return [
    'PlayerStoreRequest' => [
        'name' => 'Invalid value for name: :attribute',
        'position' => 'Invalid value for position: :attribute',
        'playerSkills' => 'Invalid value for playerSkills: :attribute',
    ],

    'SelectTeamRequest' => [
        'required' => 'The ":parameter" is required in requirement set :index',
        'distinct' => 'The "position: :attribute" must be distinct',
        'string' => 'The ":parameter: :value" must be string in requirement set :index',
        'integer' => 'The ":parameter: :value" must be integer in requirement set :index',
    ],


];

