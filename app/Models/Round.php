<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Round extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stage_id',
        'name',
        'starts_on',
        'ends_on',
        'sequence',
    ];

    protected function casts(): array
    {
        return [
            'stage_id'  => 'integer',
            'starts_on' => 'immutable_date',
            'ends_on'   => 'immutable_date',
            'sequence'  => 'integer',
        ];
    }

    // Attributes ----

    public function period(): Attribute
    {
        return Attribute::get(fn() => $this->starts_on->toPeriod($this->ends_on));
    }

    // Relationships ----

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    // Scopes ----

    /** @noinspection PhpUnused */
    public function scopeInSequence(Builder $query): Builder
    {
        return $query->orderBy('sequence');
    }
}
