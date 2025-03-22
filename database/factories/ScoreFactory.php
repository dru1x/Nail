<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\Handicap;
use App\Models\MatchResult;
use App\Models\Score;
use App\Models\SetResult;
use App\Services\MatchResultService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ScoreFactory extends Factory
{
    protected $model = Score::class;

    public function definition(): array
    {
        $handicapBefore = Handicap::inRandomOrder()->first();
        $handicapAfter  = Handicap::whereBowStyle($handicapBefore->bow_style)
            ->where('number', '<=', $handicapBefore->number)
            ->where('number', '>', $handicapBefore->number - 10)
            ->first();

        $points         = fake()->numberBetween(0, 150);
        $pointsAdjusted = $points + $handicapBefore->match_allowance;

        $resultType = fake()->randomElement(['match', 'set']);

        return [
            'result_type'           => $resultType,
            'result_id'             => $resultType == 'match' ? MatchResult::factory() : SetResult::factory(),
            'entry_id'              => Entry::factory(),
            'handicap_before'       => $handicapBefore->number,
            'handicap_after'        => $handicapAfter->number,
            'allowance'             => $handicapBefore->match_allowance,
            'match_points'          => $points,
            'match_points_adjusted' => $pointsAdjusted,
            'bonus_points'          => $points >= 1440 ? 1 : 0,
            'league_points'         => fake()->randomElement([0, 2]),
            'created_at'            => Carbon::now(),
            'updated_at'            => Carbon::now(),
        ];
    }

    // States ----

    public function win(): self
    {
        return $this->state(fn() => [
            'league_points' => MatchResultService::LEAGUE_POINTS_FOR_WIN,
        ]);
    }

    public function draw(): self
    {
        return $this->state(fn() => [
            'league_points' => MatchResultService::LEAGUE_POINTS_FOR_DRAW,
        ]);
    }

    public function loss(): self
    {
        return $this->state(fn() => [
            'league_points' => MatchResultService::LEAGUE_POINTS_FOR_LOSS,
        ]);
    }
}
