@extends('layouts.admin')

@section('title', 'Preventivo — ' . $preventivo->name)
@section('header', 'Dettaglio preventivo')

@section('content')
    <div class="mb-6">
        <a href="{{ route('preventivi.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
            &larr; Torna all'elenco
        </a>
    </div>

    <div class="grid gap-8 lg:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-base font-semibold text-gray-900">Dati del cliente</h2>
            <dl class="space-y-4 text-sm">
                <div>
                    <dt class="font-medium text-gray-500">Nome</dt>
                    <dd class="mt-1 text-gray-900">{{ $preventivo->name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Telefono</dt>
                    <dd class="mt-1">
                        <a href="tel:{{ preg_replace('/\s+/', '', $preventivo->phone) }}" class="text-blue-600 hover:text-blue-800">
                            {{ $preventivo->phone }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-gray-900">
                        @if ($preventivo->email)
                            <a href="mailto:{{ $preventivo->email }}" class="text-blue-600 hover:text-blue-800">
                                {{ $preventivo->email }}
                            </a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Messaggio</dt>
                    <dd class="mt-1 whitespace-pre-wrap text-gray-900">{{ $preventivo->message }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Ricevuto il</dt>
                    <dd class="mt-1 text-gray-900">{{ $preventivo->created_at?->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Stato attuale</dt>
                    <dd class="mt-2">
                        @include('admin.preventivi._status-badge', ['preventivo' => $preventivo])
                    </dd>
                </div>
                @if ($preventivo->follow_up_at)
                    <div>
                        <dt class="font-medium text-gray-500">Ricontatto programmato</dt>
                        <dd class="mt-1 text-gray-900">{{ $preventivo->follow_up_at->format('d/m/Y H:i') }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-base font-semibold text-gray-900">Gestione</h2>

            @if (! $calendarConfigured)
                <p class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-900">
                    Google Calendar non è configurato. Gli eventi non verranno creati automaticamente finché non aggiungi le credenziali nel file <code class="text-xs">.env</code>.
                    Vedi <code class="text-xs">docs/GOOGLE_CALENDAR.md</code>.
                </p>
            @endif

            <form method="POST" action="{{ route('preventivi.update', $preventivo) }}" class="space-y-5"
                  x-data="{ status: @js(old('status', $preventivo->status?->value)) }">
                @csrf
                @method('PATCH')

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Stato</label>
                    <select name="status" id="status" required x-model="status"
                            class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}">
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="status === @js($ricontattareStatus)" x-cloak class="space-y-2 rounded-lg border border-purple-100 bg-purple-50 p-4">
                    <label for="follow_up_at" class="block text-sm font-medium text-purple-900">
                        Data e ora del ricontatto *
                    </label>
                    <p class="text-xs text-purple-700">
                        Verrà creato un evento sul tuo Google Calendar (se configurato).
                    </p>
                    <input type="datetime-local" name="follow_up_at" id="follow_up_at"
                           value="{{ old('follow_up_at', $preventivo->follow_up_at?->format('Y-m-d\TH:i')) }}"
                           :required="status === @js($ricontattareStatus)"
                           class="block w-full rounded-lg border border-purple-200 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    @error('follow_up_at')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Note interne</label>
                    <textarea name="notes" id="notes" rows="6"
                              class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Aggiungi note sulla trattativa…">{{ old('notes', $preventivo->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Salva modifiche
                </button>
            </form>

            <div class="mt-10 border-t border-gray-200 pt-6">
                <h3 class="text-sm font-medium text-gray-900">Zona pericolosa</h3>
                <p class="mt-1 text-sm text-gray-500">
                    L'eliminazione è definitiva e non può essere annullata.
                </p>
                <button type="button"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-preventivo-deletion')"
                        class="mt-4 inline-flex items-center rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-700 shadow-sm transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Elimina preventivo
                </button>
            </div>
        </div>
    </div>

    <x-modal name="confirm-preventivo-deletion" focusable>
        <form method="POST" action="{{ route('preventivi.destroy', $preventivo) }}" class="p-6">
            @csrf
            @method('DELETE')

            <h2 class="text-lg font-medium text-gray-900">
                Eliminare questo preventivo?
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                Stai per eliminare la richiesta di <strong class="font-medium text-gray-900">{{ $preventivo->name }}</strong>
                ricevuta il {{ $preventivo->created_at?->format('d/m/Y H:i') }}.
                Questa azione è permanente.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    Annulla
                </button>

                <button type="submit"
                        class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Sì, elimina
                </button>
            </div>
        </form>
    </x-modal>
@endsection

