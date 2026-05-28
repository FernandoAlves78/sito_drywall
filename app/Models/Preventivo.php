<?php

namespace App\Models;

use App\Enums\PreventivoStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preventivo extends Model
{
    use HasFactory;

    protected $table = 'preventivi';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'status',
        'notes',
        'follow_up_at',
        'google_calendar_event_id',
    ];

    protected function casts(): array
    {
        return [
            'status' => PreventivoStatus::class,
            'follow_up_at' => 'datetime',
        ];
    }
}
