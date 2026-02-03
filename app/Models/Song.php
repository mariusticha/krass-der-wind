<?php

namespace App\Models;

use Database\Factories\SongFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Song extends Model
{
    /** @use HasFactory<SongFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'artist',
        'year',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
        ];
    }

    public function gigs(): BelongsToMany
    {
        return $this->belongsToMany(Gig::class)
            ->withPivot('order', 'notes')
            ->withTimestamps();
    }
}
