<?php

namespace Tests\Feature\Admin;

use App\Contracts\PreventivoCalendarSync;
use App\Enums\PreventivoStatus;
use App\Models\Preventivo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreventivoTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_preventivi_list(): void
    {
        $this->get(route('preventivi.index'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_preventivi_list(): void
    {
        $user = User::factory()->create();

        Preventivo::create([
            'name' => 'Mario Rossi',
            'phone' => '+393331112233',
            'email' => 'mario@example.com',
            'message' => 'Preventivo per bagno',
        ]);

        $this->actingAs($user)
            ->get(route('preventivi.index'))
            ->assertOk()
            ->assertSee('Mario Rossi')
            ->assertSee('Preventivi');
    }

    public function test_authenticated_user_can_update_status_and_notes(): void
    {
        $user = User::factory()->create();

        $preventivo = Preventivo::create([
            'name' => 'Luigi Verdi',
            'phone' => '+393339998877',
            'message' => 'Cartongesso soggiorno',
        ]);

        $this->actingAs($user)
            ->patch(route('preventivi.update', $preventivo), [
                'status' => PreventivoStatus::InTrattativa->value,
                'notes' => 'Cliente interessato, richiamare venerdì.',
            ])
            ->assertRedirect(route('preventivi.show', $preventivo));

        $preventivo->refresh();

        $this->assertSame(PreventivoStatus::InTrattativa, $preventivo->status);
        $this->assertSame('Cliente interessato, richiamare venerdì.', $preventivo->notes);
    }

    public function test_public_store_creates_preventivo_with_nuovo_status(): void
    {
        $response = $this->postJson(route('preventivo.store'), [
            'name' => 'Anna Bianchi',
            'phone' => '+393221112233',
            'email' => 'anna@example.com',
            'message' => 'Pittura interna',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('preventivi', [
            'name' => 'Anna Bianchi',
            'phone' => '+393221112233',
            'status' => PreventivoStatus::Nuovo->value,
        ]);
    }

    public function test_authenticated_user_can_delete_preventivo(): void
    {
        $user = User::factory()->create();

        $preventivo = Preventivo::create([
            'name' => 'Da eliminare',
            'phone' => '+393331112233',
            'message' => 'Test eliminazione',
        ]);

        $this->actingAs($user)
            ->delete(route('preventivi.destroy', $preventivo))
            ->assertRedirect(route('preventivi.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('preventivi', ['id' => $preventivo->id]);
    }

    public function test_guest_cannot_delete_preventivo(): void
    {
        $preventivo = Preventivo::create([
            'name' => 'Protetto',
            'phone' => '+393331112233',
            'message' => 'Test',
        ]);

        $this->delete(route('preventivi.destroy', $preventivo))
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas('preventivi', ['id' => $preventivo->id]);
    }

    public function test_ricontattare_requires_follow_up_at(): void
    {
        $user = User::factory()->create();

        $preventivo = Preventivo::create([
            'name' => 'Cliente',
            'phone' => '+393331112233',
            'message' => 'Test',
        ]);

        $this->actingAs($user)
            ->from(route('preventivi.show', $preventivo))
            ->patch(route('preventivi.update', $preventivo), [
                'status' => PreventivoStatus::RicontattareCliente->value,
                'notes' => null,
            ])
            ->assertSessionHasErrors('follow_up_at');
    }

    public function test_ricontattare_triggers_calendar_sync_when_configured(): void
    {
        $user = User::factory()->create();

        $preventivo = Preventivo::create([
            'name' => 'Cliente Calendar',
            'phone' => '+393331112233',
            'message' => 'Test calendar',
        ]);

        $followUp = now()->addDays(2)->format('Y-m-d\TH:i');

        $this->mock(PreventivoCalendarSync::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
            $mock->shouldReceive('sync')
                ->once()
                ->andReturn('https://calendar.google.com/calendar/event?eid=test');
        });

        $this->actingAs($user)
            ->patch(route('preventivi.update', $preventivo), [
                'status' => PreventivoStatus::RicontattareCliente->value,
                'follow_up_at' => $followUp,
                'notes' => 'Chiamare per preventivo',
            ])
            ->assertRedirect(route('preventivi.show', $preventivo))
            ->assertSessionHas('calendar_url');

        $preventivo->refresh();
        $this->assertNotNull($preventivo->follow_up_at);
        $this->assertSame(PreventivoStatus::RicontattareCliente, $preventivo->status);
    }

    public function test_public_store_requires_phone(): void
    {
        $response = $this->postJson(route('preventivo.store'), [
            'name' => 'Anna Bianchi',
            'message' => 'Pittura interna',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['phone']);
    }
}
