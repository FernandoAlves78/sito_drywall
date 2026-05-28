<?php

namespace App\Services;

use App\Contracts\PreventivoCalendarSync;
use App\Models\Preventivo;

class NullPreventivoCalendarSync implements PreventivoCalendarSync
{
  public function isConfigured(): bool
  {
    return false;
  }

  public function sync(Preventivo $preventivo): ?string
  {
    return null;
  }

  public function deleteFor(Preventivo $preventivo): void
  {
    //
  }
}
