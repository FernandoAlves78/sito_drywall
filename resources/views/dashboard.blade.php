@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
    <div class="mb-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Benvenuto</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ Auth::user()->name }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Preventivi</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ \App\Models\Preventivo::count() }}</p>
            <a href="{{ route('preventivi.index') }}" class="mt-2 inline-block text-sm font-medium text-blue-600 hover:text-blue-800">
                Vedi tutti &rarr;
            </a>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Recensioni</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ \App\Models\Recensione::count() }}</p>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-base font-semibold text-gray-900">Pannello di amministrazione</h2>
        <p class="text-sm text-gray-600">
            Benvenuto nel pannello di gestione di Alves Drywall &amp; Pittura.
            Utilizza il menu a sinistra per navigare tra le sezioni.
        </p>
    </div>
@endsection
