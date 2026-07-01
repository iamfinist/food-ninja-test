<?php

namespace Tests\Feature\Services;

use App\DTO\LinkDTO;
use App\Models\Link;
use App\Services\LinkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LinkServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resolve_returns_a_dto_and_caches_the_data(): void
    {
        $link = Link::factory()->create(['code' => 'abc123']);

        $resolved = app(LinkService::class)->resolve('abc123');

        $this->assertInstanceOf(LinkDTO::class, $resolved);
        $this->assertSame($link->id, $resolved->id);
        $this->assertSame($link->original_url, $resolved->originalUrl);
        $this->assertSame(
            ['id' => $link->id, 'original_url' => $link->original_url],
            Cache::get('link:abc123'),
        );
    }

    public function test_resolve_serves_from_cache_without_hitting_the_database(): void
    {
        $link = Link::factory()->create(['code' => 'abc123']);
        $service = app(LinkService::class);

        $service->resolve('abc123');
        Link::query()->where('id', $link->id)->delete();

        $resolved = $service->resolve('abc123');

        $this->assertInstanceOf(LinkDTO::class, $resolved);
        $this->assertSame($link->id, $resolved->id);
        $this->assertSame($link->original_url, $resolved->originalUrl);
    }

    public function test_resolve_returns_null_for_an_unknown_code(): void
    {
        $this->assertNull(app(LinkService::class)->resolve('missing'));
    }

    public function test_forget_invalidates_the_cache(): void
    {
        $link = Link::factory()->create(['code' => 'abc123']);
        $service = app(LinkService::class);

        $service->resolve('abc123');
        $this->assertTrue(Cache::has('link:abc123'));

        $service->forget($link);

        $this->assertFalse(Cache::has('link:abc123'));
    }

    public function test_deleting_a_link_invalidates_its_cache(): void
    {
        $link = Link::factory()->create(['code' => 'abc123']);
        $service = app(LinkService::class);

        $service->resolve('abc123');
        $this->assertTrue(Cache::has('link:abc123'));

        $link->delete();

        $this->assertFalse(Cache::has('link:abc123'));
    }
}
