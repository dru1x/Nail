<?php

namespace Database\Factories;

use App\Enums\MatchFormat;
use App\Models\Entry;
use App\Models\MatchResult;
use App\Models\Round;
use App\Models\Score;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MatchResultFactory extends Factory
{
    protected $model = MatchResult::class;

    public function definition(): array
    {
        return [
            'round_id'       => Round::factory(),
            'left_score_id'  => Score::factory(),
            'right_score_id' => Score::factory(),
            'winner_id'      => fake()->optional(0.7)->passthrough(Entry::factory()),
            'format'         => fake()->randomElement(MatchFormat::cases()),
            'shot_at'        => CarbonImmutable::make(fake()->dateTimeBetween('-2months')),
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
        ];
    }
}
