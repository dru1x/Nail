<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\MatchResult;
use App\Models\SetResult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SetResult>
 */
class SetResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'match_result_id' => MatchResult::factory(),
            'winner_id'       => fake()->optional(0.75)->passthrough(Entry::factory()),
            'sequence'        => fake()->numberBetween(0, 10),
            'created_at'      => Carbon::now(),
            'deleted_at'      => Carbon::now(),
        ];
    }

    // States ----

    public function decided(): self
    {
        return $this->state(fn() => [
            'winner_id' => Entry::factory(),
        ]);
    }

    public function draw(): self
    {
        return $this->state(fn() => [
            'winner_id' => null,
        ]);
    }
}
