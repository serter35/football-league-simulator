<?php

namespace App\Models;

use App\Casts\GoalCountCast;
use App\Casts\TeamPowerCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'power', 'points', 'played', 'won', 'drawn', 'lost',
        'goals_for', 'goals_against', 'goal_difference',
    ];

    protected $casts = [
        'power' => TeamPowerCast::class,
        'points' => GoalCountCast::class, // Puan da negatif olamaz, GoalCount kuralları geçerli
        'goals_for' => GoalCountCast::class,
        'goals_against' => GoalCountCast::class,
        'played' => 'integer',
        'won' => 'integer',
        'drawn' => 'integer',
        'lost' => 'integer',
        'goal_difference' => 'integer',
    ];
}
