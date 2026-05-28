<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class GoogleCalendarService
{
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';

    private const CALENDAR_API = 'https://www.googleapis.com/calendar/v3';

    public function isConfigured(): bool
    {
        return (bool) config('services.google_calendar.client_id')
            && (bool) config('services.google_calendar.client_secret')
            && (bool) config('services.google_calendar.refresh_token');
    }

    public function calendarId(): string
    {
        return config('services.google_calendar.calendar_id', 'primary');
    }

    public function durationMinutes(): int
    {
        return (int) config('services.google_calendar.event_duration_minutes', 30);
    }

    /**
     * @return array{id: string, htmlLink: string|null}
     */
    public function createEvent(string $summary, string $description, Carbon $startsAt): array
    {
        $response = $this->request()
            ->post($this->eventsUrl(), $this->eventPayload($summary, $description, $startsAt))
            ->throw();

        return [
            'id' => $response->json('id'),
            'htmlLink' => $response->json('htmlLink'),
        ];
    }

    /**
     * @return array{id: string, htmlLink: string|null}
     */
    public function updateEvent(string $eventId, string $summary, string $description, Carbon $startsAt): array
    {
        $response = $this->request()
            ->put($this->eventsUrl($eventId), $this->eventPayload($summary, $description, $startsAt))
            ->throw();

        return [
            'id' => $response->json('id'),
            'htmlLink' => $response->json('htmlLink'),
        ];
    }

    public function deleteEvent(string $eventId): void
    {
        $this->request()
            ->delete($this->eventsUrl($eventId))
            ->throw();
    }

    private function request()
    {
        return Http::withToken($this->accessToken())
            ->acceptJson()
            ->asJson();
    }

    private function accessToken(): string
    {
        $response = Http::asForm()->post(self::TOKEN_URL, [
            'client_id' => config('services.google_calendar.client_id'),
            'client_secret' => config('services.google_calendar.client_secret'),
            'refresh_token' => config('services.google_calendar.refresh_token'),
            'grant_type' => 'refresh_token',
        ]);

        if ($response->failed()) {
            throw new RequestException($response);
        }

        $token = $response->json('access_token');

        if (! is_string($token) || $token === '') {
            throw new \RuntimeException('Google OAuth não devolveu access_token.');
        }

        return $token;
    }

    private function eventsUrl(?string $eventId = null): string
    {
        $calendarId = urlencode($this->calendarId());
        $base = self::CALENDAR_API."/calendars/{$calendarId}/events";

        return $eventId ? "{$base}/{$eventId}" : $base;
    }

    /** @return array<string, mixed> */
    private function eventPayload(string $summary, string $description, Carbon $startsAt): array
    {
        $endsAt = $startsAt->copy()->addMinutes($this->durationMinutes());
        $timeZone = config('app.timezone', 'Europe/Rome');

        return [
            'summary' => $summary,
            'description' => $description,
            'start' => [
                'dateTime' => $startsAt->toIso8601String(),
                'timeZone' => $timeZone,
            ],
            'end' => [
                'dateTime' => $endsAt->toIso8601String(),
                'timeZone' => $timeZone,
            ],
        ];
    }
}
