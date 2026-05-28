@extends('layouts.admin')

@section('title', 'Preventivi')
@section('header', 'Preventivi')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-600">Gestisci le richieste di preventivo ricevute dal sito.</p>
        <form method="GET" action="{{ route('preventivi.index') }}" class="flex items-center gap-2">
            <label for="status" class="text-sm font-medium text-gray-700">Filtra:</label>
            <select name="status" id="status"
                    onchange="this.form.submit()"
                    class="rounded-lg border border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Tutti gli stati</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected($filterStatus === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        @if ($preventivi->isEmpty())
            <p class="p-8 text-center text-sm text-gray-500">Nessun preventivo trovato.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Telefono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Stato</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($preventivi as $preventivo)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $preventivo->created_at?->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $preventivo->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $preventivo->phone }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $preventivo->email ?: '—' }}</td>
                                <td class="px-6 py-4">
                                    @include('admin.preventivi._status-badge', ['preventivo' => $preventivo])
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                    <a href="{{ route('preventivi.show', $preventivo) }}"
                                       class="font-medium text-blue-600 hover:text-blue-800">
                                        Dettagli
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 px-6 py-4">
                {{ $preventivi->links() }}
            </div>
        @endif
    </div>
@endsection
