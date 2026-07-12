<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_bw',
        'price_color',
        'price_duplex',
        'price_a4',
        'price_f4',
        'price_a3',
        'is_active',
    ];

    protected $casts = [
        'price_bw' => 'decimal:2',
        'price_color' => 'decimal:2',
        'price_duplex' => 'decimal:2',
        'price_a4' => 'decimal:2',
        'price_f4' => 'decimal:2',
        'price_a3' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Service $service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->name);
            }
        });
    }
}
