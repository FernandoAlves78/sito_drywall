<?php

namespace App\Models;

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
    ];
}
