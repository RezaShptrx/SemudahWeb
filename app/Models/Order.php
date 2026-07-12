<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Order extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;


    protected static function booted(): void
    {
        static::updating(function (Order $order) {
            // Prevent changing status from SELESAI
            if ($order->getOriginal('status') === OrderStatus::SELESAI && $order->isDirty('status')) {
                throw new \Exception('Pesanan yang sudah selesai tidak dapat diubah statusnya.');
            }
        });

        static::saving(function (Order $order) {
            // If status is changed to SELESAI, set completed_at
            if ($order->isDirty('status') && $order->status === OrderStatus::SELESAI) {
                $order->completed_at = now();
            }
        });

        static::saved(function (Order $order) {
            // Jika status berubah menjadi SELESAI, otomatis hapus file unggahan
            if ($order->wasChanged('status') && $order->status === OrderStatus::SELESAI) {
                $order->clearMediaCollection('order_files');
            }
        });
    }

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'status',
        'total_price',
        'discount_amount',
        'final_price',
        'payment_method',
        'payment_status',
        'notes',
        'internal_notes',
        'completed_at',
        'taken_at',
        'verified_by',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'payment_method' => PaymentMethod::class,
        'payment_status' => PaymentStatus::class,
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'completed_at' => 'datetime',
        'taken_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
