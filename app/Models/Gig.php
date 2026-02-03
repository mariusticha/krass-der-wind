<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gig extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'time' => 'datetime',
            'is_public' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['rsvp_status', 'attended', 'rsvp_at', 'attended_at'])
            ->withTimestamps();
    }

    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class)
            ->withPivot('order', 'notes')
            ->withTimestamps()
            ->orderByPivot('order');
    }

    public function scopeUpcoming(Builder $query): void
    {
        $query->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time');
    }

    public function scopePast(Builder $query): void
    {
        $query->where('date', '<', now()->toDateString())
            ->orderByDesc('date')
            ->orderByDesc('time');
    }

    public function scopePublic(Builder $query): void
    {
        $query->where('is_public', true);
    }
}
