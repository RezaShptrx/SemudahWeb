<?php

namespace App\Livewire\Components; // Wait, plan.md puts it under app/Livewire/Forms/ReviewOrderForm.php
// Let's use namespace App\Livewire\Forms; to match directory.
namespace App\Livewire\Forms;

use App\Models\PromoCode;
use App\Services\OrderService;
use Livewire\Component;
use Livewire\Attributes\On;

class ReviewOrderForm extends Component
{
    public $orderData = [];
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';
    public $notes = '';
    public $promoCode = '';
    public $appliedPromo = null;
    public $totalPrice = 0;
    public $paymentMethod = 'qris'; // qris, tunai

    protected $rules = [
        'customerName' => 'required|string|min:3',
        'customerEmail' => 'required|email',
        'customerPhone' => 'required|digits_between:10,15',
        'paymentMethod' => 'required|in:qris,tunai',
        'notes' => 'nullable|string',
        'promoCode' => 'nullable|string',
    ];

    #[On('order-data-ready')]
    public function receiveOrderData($data)
    {
        $this->orderData = $data;
        $this->totalPrice = $data['total_price'] ?? 0;
        $this->paymentMethod = $data['payment_method'] ?? 'qris';
    }

    public function applyPromo()
    {
        $promo = PromoCode::where([
            'code' => strtoupper($this->promoCode),
            'is_active' => true
        ])
        ->where('valid_from', '<=', now())
        ->where('valid_until', '>=', now())
        ->first();

        if (!$promo) {
            session()->flash('promo_error', 'Kode promo tidak valid');
            return;
        }

        if ($promo->max_usage !== null && $promo->used_count >= $promo->max_usage) {
            session()->flash('promo_error', 'Kode promo sudah mencapai batas penggunaan');
            return;
        }

        if ($this->orderData['subtotal'] < $promo->min_order_amount) {
            session()->flash('promo_error', 'Subtotal belum mencapai minimal order ' . number_format($promo->min_order_amount, 0, ',', '.'));
            return;
        }

        $this->appliedPromo = $promo;
        $this->recalculateTotal();
        session()->flash('promo_success', 'Kode promo berhasil diterapkan');
    }

    public function removePromo()
    {
        $this->appliedPromo = null;
        $this->promoCode = '';
        $this->recalculateTotal();
    }

    private function recalculateTotal()
    {
        $subtotal = $this->orderData['subtotal'] ?? 0;
        $discount = 0;

        if ($this->appliedPromo) {
            if ($this->appliedPromo->discount_type->value === 'percentage') {
                $discount = ($subtotal * $this->appliedPromo->discount_value) / 100;
            } else {
                $discount = $this->appliedPromo->discount_value;
            }
        }

        $this->totalPrice = max(0.00, $subtotal - $discount);
    }

    public function submitOrder()
    {
        $this->validate();

        $order = app(OrderService::class)->createOrder(
            customerName: $this->customerName,
            customerEmail: $this->customerEmail,
            customerPhone: $this->customerPhone,
            items: $this->orderData['items'] ?? [],
            totalPrice: $this->orderData['subtotal'] ?? 0,
            paymentMethod: $this->paymentMethod,
            notes: $this->notes,
            promoCode: $this->appliedPromo,
        );

        return redirect()->route('order.success', $order->order_number);
    }

    public function render()
    {
        return view('livewire.forms.review-order-form');
    }
}
