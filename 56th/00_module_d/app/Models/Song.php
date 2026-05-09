<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Song extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'album_id',
        'title',
        'duration_seconds',
        'lyrics',
        'order',
        'view_count',
        'is_cover',
        'cover_image_path',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'order' => 'integer',
        'view_count' => 'integer',
        'is_cover' => 'boolean',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'song_label');
    }
}
