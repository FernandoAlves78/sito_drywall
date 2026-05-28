<?php

namespace App\Enums;

enum PreventivoStatus: string
{
    case Nuovo = 'nuovo';
    case Contattato = 'contattato';
    case InTrattativa = 'in_trattativa';
    case Annullato = 'annullato';
    case PreventivoAccettato = 'preventivo_accettato';
    case RicontattareCliente = 'ricontattare_cliente';

    public function label(): string
    {
        return match ($this) {
            self::Nuovo => 'Nuovo',
            self::Contattato => 'Contattato',
            self::InTrattativa => 'In trattativa',
            self::Annullato => 'Annullato',
            self::PreventivoAccettato => 'Preventivo accettato',
            self::RicontattareCliente => 'Ricontattare cliente',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Nuovo => 'bg-slate-100 text-slate-700',
            self::Contattato => 'bg-blue-100 text-blue-800',
            self::InTrattativa => 'bg-amber-100 text-amber-800',
            self::Annullato => 'bg-gray-100 text-gray-600',
            self::PreventivoAccettato => 'bg-green-100 text-green-800',
            self::RicontattareCliente => 'bg-purple-100 text-purple-800',
        };
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
