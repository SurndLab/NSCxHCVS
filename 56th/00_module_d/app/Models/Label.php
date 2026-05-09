<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Label extends Model
{
    protected $fillable = ['name'];

    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'song_label');
    }
}
