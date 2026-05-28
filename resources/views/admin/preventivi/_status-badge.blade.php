@php
    $status = $preventivo->status ?? \App\Enums\PreventivoStatus::Nuovo;
@endphp
<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $status->badgeClasses() }}">
    {{ $status->label() }}
</span>
