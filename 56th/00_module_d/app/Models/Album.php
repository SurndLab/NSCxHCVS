<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'publisher_id',
        'title',
        'artist',
        'release_year',
        'genre',
        'description',
    ];

    protected $casts = [
        'release_year' => 'integer',
    ];

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class)->orderBy('order');
    }
}
