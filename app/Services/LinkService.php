<?php

namespace App\Services;

use App\DTO\LinkDTO;
use App\Models\Link;
use Illuminate\Support\Facades\Cache;

class LinkService
{
    private const int CACHE_TTL = 3600;

    public function resolve(string $code): ?LinkDTO
    {
        $data = Cache::remember(
            $this->cacheKey($code),
            self::CACHE_TTL,
            fn () => Link::where('code', $code)->first(['id', 'original_url'])?->only('id', 'original_url')
        );

        return $data === null ? null : LinkDTO::fromArray($data);
    }

    public function forget(Link $link): void
    {
        Cache::forget($this->cacheKey($link->code));
    }

    private function cacheKey(string $code): string
    {
        return "link:{$code}";
    }
}
