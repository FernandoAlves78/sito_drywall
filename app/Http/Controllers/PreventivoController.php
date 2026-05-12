<?php

namespace App\Http\Controllers;

use App\Mail\PreventivoRecebido;
use App\Models\Preventivo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PreventivoController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $preventivo = Preventivo::create($data);

        try {
            Mail::to(config('mail.from.address'))->send(new PreventivoRecebido($preventivo));
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json([
            'message' => 'Richiesta inviata!',
            'id' => $preventivo->id,
        ], 201);
    }
}
