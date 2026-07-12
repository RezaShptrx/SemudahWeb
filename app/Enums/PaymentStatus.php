<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum PaymentStatus: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case LUNAS = 'lunas';
    case GAGAL = 'gagal';
    case REFUND = 'refund';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::LUNAS => 'Lunas',
            self::GAGAL => 'Gagal',
            self::REFUND => 'Refund',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::LUNAS => 'success',
            self::GAGAL => 'danger',
            self::REFUND => 'gray',
        };
    }
}
