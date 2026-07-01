<?php

namespace App\Models;

use App\Services\LinkService;
use Database\Factories\LinkFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['user_id', 'code', 'original_url'])]
class Link extends Model
{
    /** @use HasFactory<LinkFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Link $link): void {
            if (empty($link->code)) {
                $link->code = static::generateUniqueCode();
            }
        });

        static::deleted(function (Link $link): void {
            app(LinkService::class)->forget($link);
        });
    }

    public static function generateUniqueCode(int $length = 6): string
    {
        do {
            $code = Str::lower(Str::random($length));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Click, $this>
     */
    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    public function shortUrl(): string
    {
        return url($this->code);
    }
}
