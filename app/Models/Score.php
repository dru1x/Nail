<?php

namespace App\Models;

use App\Enums\ResultType;
use App\Enums\Side;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Score extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'result_type',
        'result_id',
        'entry_id',
        'side',
        'handicap_before',
        'handicap_after',
        'allowance',
        'match_points',
        'match_points_adjusted',
        'bonus_points',
        'league_points',
    ];

    protected $casts = [
        'result_type'           => ResultType::class,
        'result_id'             => 'integer',
        'entry_id'              => 'integer',
        'side'                  => Side::class,
        'handicap_before'       => 'integer',
        'handicap_after'        => 'integer',
        'handicap_change'       => 'integer', #TODO: Add this to the table and remove the dynamic attribute
        'allowance'             => 'integer',
        'match_points'          => 'integer',
        'match_points_adjusted' => 'integer',
        'bonus_points'          => 'integer',
        'league_points'         => 'integer',
    ];

    // Attributes ----

    public function handicapChange(): Attribute
    {
        return Attribute::get(fn() => $this->handicap_before - $this->handicap_after);
    }

    // Relationships ----

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    public function result(): MorphTo
    {
        return $this->morphTo('result');
    }
}
