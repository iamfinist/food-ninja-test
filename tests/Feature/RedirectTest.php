<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Services\ClickService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_short_link_redirects_to_the_original_url(): void
    {
        $link = Link::factory()->create([
            'code' => 'abc123',
            'original_url' => 'https://example.com/page',
        ]);

        $response = $this->get('/'.$link->code);

        $response->assertRedirect('https://example.com/page');
    }

    public function test_visiting_a_short_link_records_a_click_with_ip_and_timestamp(): void
    {
        $link = Link::factory()->create(['code' => 'abc123']);

        $this->get('/'.$link->code, ['REMOTE_ADDR' => '203.0.113.9']);

        $this->assertCount(1, $link->clicks()->get());

        $click = $link->clicks()->first();
        $this->assertSame('203.0.113.9', $click->ip_address);
        $this->assertNotNull($click->created_at);
    }

    public function test_each_recorded_click_is_persisted(): void
    {
        $link = Link::factory()->create();
        $clicks = app(ClickService::class);

        $clicks->record($link->id, '10.0.0.1');
        $clicks->record($link->id, '10.0.0.2');
        $clicks->record($link->id, '10.0.0.3');

        $this->assertSame(3, $link->clicks()->count());
    }

    public function test_unknown_short_code_returns_404(): void
    {
        $this->get('/missing1')->assertNotFound();
    }
}
