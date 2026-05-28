<?php

namespace App\Contracts;

use App\Models\Preventivo;

interface PreventivoCalendarSync
{
    public function isConfigured(): bool;

    /** Sync calendar event after preventivo was saved. Returns optional event URL. */
    public function sync(Preventivo $preventivo): ?string;

    public function deleteFor(Preventivo $preventivo): void;
}
