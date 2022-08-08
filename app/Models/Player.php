<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW.
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    use HasFactory;

    const POSITION_DEFENDER = "defender";
    const POSITION_MIDFIELDER = "midfielder";
    const POSITION_FORWARD = "forward";
    const POSITIONS = [
        self::POSITION_DEFENDER => 'مدافع',
        self::POSITION_MIDFIELDER => 'هافبک',
        self::POSITION_FORWARD => 'ماهجم',
    ];

    protected $fillable = [
        'name',
        'position'
    ];

    public function playerSkills(): HasMany
    {
        return $this->hasMany(PlayerSkill::class);
    }
}
