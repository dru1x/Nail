<?php

namespace App\Models;

use App\Enums\MatchFormat;
use App\Enums\Side;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'round_id',
        'winner_id',
        'next_match_result_id',
        'format',
        'shot_at',
    ];

    protected $casts = [
        'round_id'             => 'integer',
        'winner_id'            => 'integer',
        'next_match_result_id' => 'integer',
        'format'               => MatchFormat::class,
        'shot_at'              => 'immutable_datetime',
    ];

    // Attributes ----

    public function hasWinner(): Attribute
    {
        return Attribute::get(fn() => !!$this->winner_id);
    }

    public function isDraw(): Attribute
    {
        return Attribute::get(fn() => !$this->winner_id);
    }

    // Relationships ----

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function nextMatchResult(): BelongsTo
    {
        return $this->belongsTo(MatchResult::class, 'next_match_result_id');
    }

    public function setResults(): HasMany
    {
        return $this->hasMany(SetResult::class);
    }

    public function scores(): MorphMany
    {
        return $this->morphMany(Score::class, 'result');
    }

    public function leftScore(): MorphOne
    {
        return $this->scores()
            ->where('side', Side::Left)
            ->one();
    }

    public function rightScore(): MorphOne
    {
        return $this->scores()
            ->where('side', Side::Right)
            ->one();
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Entry::class, 'winner_id');
    }

    // Scopes ----

    /** @noinspection PhpUnused */
    public function scopeInCompetition(Builder $query, Competition $competition): Builder
    {
        return $query->whereHas('round', fn(Builder|Round $round) => $round
            ->whereHas('stage', fn(Builder|Stage $stage) => $stage
                ->whereCompetitionId($competition->id)
            )
        );
    }

    /** @noinspection PhpUnused */
    public function scopeInStage(Builder $query, Stage $stage): Builder
    {
        return $query->whereHas('round', fn(Builder|Round $round) => $round->whereStageId($stage->id));
    }

    /** @noinspection PhpUnused */
    public function scopeInRound(Builder $query, Round $round): Builder
    {
        return $query->where('round_id', $round->id);
    }

    /** @noinspection PhpUnused */
    public function scopeShotBefore(Builder $query, CarbonInterface $dateTime): Builder
    {
        return $query->where('shot_at', '<', $dateTime);
    }

    /** @noinspection PhpUnused */
    public function scopeShotBy(Builder $query, Entry $entry): Builder
    {
        return $query->whereHas('scores', fn(Builder|Score $score) => $score->whereEntryId($entry->id));
    }

    /** @noinspection PhpUnused */
    public function scopeShotByBoth(Builder $query, Entry $firstEntry, Entry $secondEntry): Builder
    {
        return $query->whereHas('scores', fn(Builder|Score $score) => $score
            ->whereIn('entry_id', [$firstEntry->id, $secondEntry->id]), count: 2
        );
    }
}
