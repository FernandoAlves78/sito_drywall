<?php

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$calendar = $app->make(App\Services\GoogleCalendarService::class);

echo 'configured: '.($calendar->isConfigured() ? 'yes' : 'no').PHP_EOL;

if (! $calendar->isConfigured()) {
    echo "Missing: client_id=".((bool) config('services.google_calendar.client_id') ? 'ok' : 'NO');
    echo ', client_secret='.((bool) config('services.google_calendar.client_secret') ? 'ok' : 'NO');
    echo ', refresh_token='.((bool) config('services.google_calendar.refresh_token') ? 'ok' : 'NO').PHP_EOL;
    exit(1);
}

echo 'calendar_id: '.$calendar->calendarId().PHP_EOL;
echo 'timezone: '.config('app.timezone').PHP_EOL;

$clientId = config('services.google_calendar.client_id');
$clientSecret = config('services.google_calendar.client_secret');
$refreshToken = config('services.google_calendar.refresh_token');

echo 'client_id_ends: '.substr($clientId, -30).PHP_EOL;

$response = Illuminate\Support\Facades\Http::asForm()->post('https://oauth2.googleapis.com/token', [
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'refresh_token' => $refreshToken,
    'grant_type' => 'refresh_token',
]);

echo 'token_http (web+secret): '.$response->status().PHP_EOL;

if ($response->failed()) {
    echo 'token_error: '.($response->json('error') ?? 'unknown').PHP_EOL;
    echo 'token_desc: '.substr((string) ($response->json('error_description') ?? $response->body()), 0, 200).PHP_EOL;

    $desktopTry = Illuminate\Support\Facades\Http::asForm()->post('https://oauth2.googleapis.com/token', [
        'client_id' => $clientId,
        'refresh_token' => $refreshToken,
        'grant_type' => 'refresh_token',
    ]);
    echo 'token_http (desktop, no secret): '.$desktopTry->status().PHP_EOL;
    echo 'desktop_error: '.($desktopTry->json('error') ?? 'ok').PHP_EOL;

    if ($response->json('error') === 'unauthorized_client') {
        echo PHP_EOL.'HINT: unauthorized_client = refresh_token nao pertence a este Client ID/Secret.'.PHP_EOL;
        echo 'No OAuth Playground: engrenagem -> Use your own OAuth credentials -> MESMO ID/Secret do .env'.PHP_EOL;
        echo 'Depois Authorize + Exchange de novo e copia o refresh_token novo.'.PHP_EOL;
    }

    exit(1);
}

$accessToken = $response->json('access_token');
if (! is_string($accessToken) || $accessToken === '') {
    echo "access_token: missing in response\n";
    exit(1);
}

echo "access_token: ok\n";

$startsAt = now()->addDay()->setTime(10, 0);
$summary = '[TEST] Alves Drywall - integração calendar';
$description = 'Evento de teste automático. Podes apagar no Google Calendar.';

try {
    $event = $calendar->createEvent($summary, $description, $startsAt);
    echo "create_event: ok\n";
    echo 'event_id: '.$event['id'].PHP_EOL;
    echo 'html_link: '.($event['htmlLink'] ?? '(none)').PHP_EOL;

    $calendar->deleteEvent($event['id']);
    echo "delete_event: ok (test event removed)\n";
} catch (Throwable $e) {
    echo 'create_event: FAILED'.PHP_EOL;
    echo 'message: '.$e->getMessage().PHP_EOL;
    exit(1);
}

echo "ALL_OK\n";
