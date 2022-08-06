<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW.
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerSkill extends Model
{
    use HasFactory;

    const SKILL_DEFENSE = "defense";
    const SKILL_ATTACK = "attack";
    const SKILL_SPEED = "speed";
    const SKILL_STRENGTH = "strength";
    const SKILL_STAMINA = "stamina";
    const SKILLS = [
      self::SKILL_DEFENSE => 'دفاع',
      self::SKILL_ATTACK => 'حمله',
      self::SKILL_SPEED => 'سرعت',
      self::SKILL_STRENGTH => 'قدرت',
      self::SKILL_STAMINA => 'استقامت',
    ];

    protected $fillable = [
        'skill',
        'value',
        'player_id',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
