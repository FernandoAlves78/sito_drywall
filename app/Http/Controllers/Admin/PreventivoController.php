<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\PreventivoCalendarSync;
use App\Enums\PreventivoStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePreventivoRequest;
use App\Models\Preventivo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PreventivoController extends Controller
{
    public function __construct(
        private readonly PreventivoCalendarSync $calendarSync,
    ) {}

    public function index(Request $request): View
    {
        $query = Preventivo::query()->latest();

        if ($request->filled('status') && in_array($request->status, PreventivoStatus::values(), true)) {
            $query->where('status', $request->status);
        }

        $preventivi = $query->paginate(15)->withQueryString();

        return view('admin.preventivi.index', [
            'preventivi' => $preventivi,
            'statuses' => PreventivoStatus::cases(),
            'filterStatus' => $request->status,
        ]);
    }

    public function show(Preventivo $preventivo): View
    {
        return view('admin.preventivi.show', [
            'preventivo' => $preventivo,
            'statuses' => PreventivoStatus::cases(),
            'calendarConfigured' => $this->calendarSync->isConfigured(),
            'ricontattareStatus' => PreventivoStatus::RicontattareCliente->value,
        ]);
    }

    public function update(UpdatePreventivoRequest $request, Preventivo $preventivo): RedirectResponse
    {
        $data = $request->validated();

        if ($data['status'] !== PreventivoStatus::RicontattareCliente->value) {
            $data['follow_up_at'] = null;
        }

        $preventivo->update($data);
        $preventivo->refresh();

        $calendarUrl = null;
        $calendarError = null;

        try {
            $calendarUrl = $this->calendarSync->sync($preventivo);
        } catch (\Throwable $e) {
            $calendarError = 'Il preventivo è stato salvato, ma non è stato possibile aggiornare Google Calendar. Verifica la configurazione API.';
            report($e);
        }

        $redirect = redirect()
            ->route('preventivi.show', $preventivo)
            ->with('success', 'Preventivo aggiornato con successo.');

        if ($calendarUrl) {
            $redirect->with('calendar_url', $calendarUrl)
                ->with('success', 'Preventivo aggiornato. Evento creato/aggiornato su Google Calendar.');
        }

        if ($calendarError) {
            $redirect->with('warning', $calendarError);
        } elseif (
            $preventivo->status === PreventivoStatus::RicontattareCliente
            && $preventivo->follow_up_at
            && ! $this->calendarSync->isConfigured()
        ) {
            $redirect->with(
                'warning',
                'Data di ricontatto salvata. Configura Google Calendar nel file .env per creare eventi automaticamente.',
            );
        }

        return $redirect;
    }

    public function destroy(Preventivo $preventivo): RedirectResponse
    {
        $this->calendarSync->deleteFor($preventivo);
        $preventivo->delete();

        return redirect()
            ->route('preventivi.index')
            ->with('success', 'Preventivo eliminato con successo.');
    }
}
