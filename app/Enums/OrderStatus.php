<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum OrderStatus: string implements HasLabel, HasColor
{
    case MENUNGGU_ANTRIAN = 'menunggu_antrian';
    case DIPROSES = 'diproses';
    case SELESAI = 'selesai';
    case DIBATALKAN = 'dibatalkan';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MENUNGGU_ANTRIAN => 'Menunggu Antrian',
            self::DIPROSES => 'Diproses',
            self::SELESAI => 'Selesai',
            self::DIBATALKAN => 'Dibatalkan',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::MENUNGGU_ANTRIAN => 'warning',
            self::DIPROSES => 'info',
            self::SELESAI => 'success',
            self::DIBATALKAN => 'danger',
        };
    }
}
