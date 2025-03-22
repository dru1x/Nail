<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'match_result_id',
        'winner_id',
        'sequence',
    ];

    protected $casts = [
        'match_result_id' => 'int',
        'winner_id'       => 'int',
        'sequence'        => 'int',
    ];

    // Relationships ----

    public function matchResult(): BelongsTo
    {
        return $this->belongsTo(MatchResult::class);
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Entry::class, 'winner_id');
    }

    // Scopes ----

    /** @noinspection PhpUnused */
    public function scopeInSequence(Builder $query): Builder
    {
        return $query->orderBy('sequence');
    }
}
