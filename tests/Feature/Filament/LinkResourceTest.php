<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\LinkResource\Pages\CreateLink;
use App\Filament\Resources\LinkResource\Pages\ListLinks;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LinkResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_short_link(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(CreateLink::class)
            ->fillForm(['original_url' => 'https://example.com/page'])
            ->call('create')
            ->assertHasNoFormErrors();

        $link = Link::first();

        $this->assertNotNull($link);
        $this->assertSame($user->id, $link->user_id);
        $this->assertSame('https://example.com/page', $link->original_url);
        $this->assertNotEmpty($link->code);
    }

    public function test_original_url_must_be_a_valid_url(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(CreateLink::class)
            ->fillForm(['original_url' => 'not-a-url'])
            ->call('create')
            ->assertHasFormErrors(['original_url']);
    }

    public function test_list_only_shows_the_authenticated_users_links(): void
    {
        $user = User::factory()->create();
        $ownLink = Link::factory()->for($user)->create();
        $otherLink = Link::factory()->create();

        Livewire::actingAs($user)
            ->test(ListLinks::class)
            ->assertCanSeeTableRecords([$ownLink])
            ->assertCanNotSeeTableRecords([$otherLink]);
    }

    public function test_user_can_delete_their_own_link(): void
    {
        $user = User::factory()->create();
        $link = Link::factory()->for($user)->create();

        Livewire::actingAs($user)
            ->test(ListLinks::class)
            ->callTableAction('delete', $link);

        $this->assertModelMissing($link);
    }

    public function test_links_can_be_searched_by_short_url(): void
    {
        $user = User::factory()->create();
        $wanted = Link::factory()->for($user)->create(['code' => 'wanted']);
        $other = Link::factory()->for($user)->create(['code' => 'other1']);

        Livewire::actingAs($user)
            ->test(ListLinks::class)
            ->searchTable('wanted')
            ->assertCanSeeTableRecords([$wanted])
            ->assertCanNotSeeTableRecords([$other])
            ->searchTable($wanted->shortUrl())
            ->assertCanSeeTableRecords([$wanted])
            ->assertCanNotSeeTableRecords([$other]);
    }
}
