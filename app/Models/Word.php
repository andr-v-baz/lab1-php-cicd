<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'original',
        'translation',
        'language_id',
        'user_id',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

    public function getInfo(): string
    {
        return "{$this->original} → {$this->translation} ({$this->language?->name})";
    }
}
