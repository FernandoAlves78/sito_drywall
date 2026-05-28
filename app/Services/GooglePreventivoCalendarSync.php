<?php

namespace App\Services;

use App\Contracts\PreventivoCalendarSync;
use App\Enums\PreventivoStatus;
use App\Models\Preventivo;
use Illuminate\Support\Facades\Log;

class GooglePreventivoCalendarSync implements PreventivoCalendarSync
{
    public function __construct(
        private readonly GoogleCalendarService $calendar,
    ) {}

    public function isConfigured(): bool
    {
        return $this->calendar->isConfigured();
    }

    public function sync(Preventivo $preventivo): ?string
    {
        if (! $this->isConfigured()) {
            return null;
        }

        if ($preventivo->status !== PreventivoStatus::RicontattareCliente || ! $preventivo->follow_up_at) {
            $this->deleteFor($preventivo);

            return null;
        }

        $summary = 'Ricontattare: '.$preventivo->name;
        $description = $this->buildDescription($preventivo);
        $startsAt = $preventivo->follow_up_at;

        try {
            if ($preventivo->google_calendar_event_id) {
                $event = $this->calendar->updateEvent(
                    $preventivo->google_calendar_event_id,
                    $summary,
                    $description,
                    $startsAt,
                );
            } else {
                $event = $this->calendar->createEvent($summary, $description, $startsAt);
                $preventivo->forceFill(['google_calendar_event_id' => $event['id']])->saveQuietly();
            }

            return $event['htmlLink'] ?? null;
        } catch (\Throwable $e) {
            Log::error('Google Calendar sync failed for preventivo '.$preventivo->id, [
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function deleteFor(Preventivo $preventivo): void
    {
        if (! $this->isConfigured() || ! $preventivo->google_calendar_event_id) {
            return;
        }

        try {
            $this->calendar->deleteEvent($preventivo->google_calendar_event_id);
        } catch (\Throwable $e) {
            Log::warning('Google Calendar delete failed for preventivo '.$preventivo->id, [
                'exception' => $e->getMessage(),
            ]);
        }

        $preventivo->forceFill(['google_calendar_event_id' => null])->saveQuietly();
    }

    private function buildDescription(Preventivo $preventivo): string
    {
        $lines = [
            'Cliente: '.$preventivo->name,
            'Telefono: '.$preventivo->phone,
            'Email: '.($preventivo->email ?: '—'),
            '',
            'Messaggio:',
            $preventivo->message,
            '',
            'Pannello: '.route('preventivi.show', $preventivo),
        ];

        if ($preventivo->notes) {
            $lines[] = '';
            $lines[] = 'Note interne:';
            $lines[] = $preventivo->notes;
        }

        return implode("\n", $lines);
    }
}
