<?php

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$id = (string) config('services.google_calendar.client_id');
$secret = (string) config('services.google_calendar.client_secret');
$refresh = (string) config('services.google_calendar.refresh_token');

$issues = [];

if ($id === '' || $id === '...') {
    $issues[] = 'GOOGLE_CALENDAR_CLIENT_ID empty or placeholder';
} elseif (! str_ends_with($id, '.apps.googleusercontent.com')) {
    $issues[] = 'CLIENT_ID should end with .apps.googleusercontent.com';
}

if (trim($id) !== $id) {
    $issues[] = 'CLIENT_ID has leading/trailing spaces';
}

if ($secret === '' || $secret === '...') {
    $issues[] = 'GOOGLE_CALENDAR_CLIENT_SECRET empty or placeholder';
} elseif (! str_starts_with($secret, 'GOCSPX-')) {
    $issues[] = 'CLIENT_SECRET usually starts with GOCSPX- (check copy/paste)';
}

if (trim($secret) !== $secret) {
    $issues[] = 'CLIENT_SECRET has leading/trailing spaces';
}

if ($refresh === '' || $refresh === '...') {
    $issues[] = 'GOOGLE_CALENDAR_REFRESH_TOKEN empty or placeholder';
} elseif (strlen($refresh) < 20) {
    $issues[] = 'REFRESH_TOKEN seems too short (truncated?)';
}

if (trim($refresh) !== $refresh) {
    $issues[] = 'REFRESH_TOKEN has leading/trailing spaces';
}

if (str_contains($refresh, "\n") || str_contains($refresh, "\r")) {
    $issues[] = 'REFRESH_TOKEN contains line breaks';
}

echo 'client_id_len: '.strlen($id).PHP_EOL;
echo 'client_secret_len: '.strlen($secret).PHP_EOL;
echo 'refresh_token_len: '.strlen($refresh).PHP_EOL;

if ($issues === []) {
    echo "format_check: ok\n";
} else {
    echo "format_check: problems\n";
    foreach ($issues as $issue) {
        echo '  - '.$issue.PHP_EOL;
    }
}
