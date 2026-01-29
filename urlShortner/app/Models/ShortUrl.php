<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'original_url',
        'short_code',
        'clicks',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function incrementClicks(): void
    {
        $this->increment('clicks');
    }

    public function getShortUrlAttribute(): string
    {
        return url('/' . $this->short_code);
    }
}
