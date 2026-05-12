<?php

namespace App\Http\Controllers;

use App\Models\Recensione;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecensioneController extends Controller
{
    public function index(): JsonResponse
    {
        $recensioni = Recensione::query()
            ->where('approved', true)
            ->latest()
            ->limit(20)
            ->get(['nome', 'testo']);

        return response()->json($recensioni);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'testo' => ['required', 'string'],
        ]);

        $recensione = Recensione::create([
            'nome' => $data['nome'],
            'testo' => $data['testo'],
            'approved' => true,
        ]);

        return response()->json([
            'message' => 'Recensione aggiunta!',
            'id' => $recensione->id,
        ], 201);
    }
}
