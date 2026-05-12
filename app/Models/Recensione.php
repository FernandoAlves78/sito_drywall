<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recensione extends Model
{
    use HasFactory;

    protected $table = 'recensioni';

    protected $fillable = [
        'nome',
        'testo',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];
}
