<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use App\Services\PriceCalculationService;
use Livewire\Component;
use Livewire\Attributes\On;

class TotebagOrderForm extends Component
{
    public $product;
    public $selectedSize = '30x40 cm';
    public $selectedColor = 'Natural';
    public $selectedMaterial = 'Kanvas';
    public $quantity = 1;
    public $notes = '';
    public $uploadedFiles = [];
    public $calculatedPrice = 0;
    public $priceBreakdown = [];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->calculatePrice();
    }

    #[On('files-uploaded')]
    public function handleFilesUploaded($files)
    {
        $this->uploadedFiles = array_merge($this->uploadedFiles, $files);
    }

    #[On('file-removed')]
    public function handleFileRemoved($index)
    {
        array_splice($this->uploadedFiles, $index, 1);
    }

    public function calculatePrice()
    {
        $specs = [
            'ukuran' => $this->selectedSize,
            'warna' => $this->selectedColor,
            'bahan' => $this->selectedMaterial,
        ];

        $this->priceBreakdown = app(PriceCalculationService::class)
            ->calculatePrice($this->product, $specs, $this->quantity);

        $this->calculatedPrice = $this->priceBreakdown['final_price'];
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['selectedSize', 'selectedColor', 'selectedMaterial', 'quantity'])) {
            if ($propertyName === 'quantity' && $this->quantity < 1) $this->quantity = 1;
            $this->calculatePrice();
        }
    }

    public function incrementQuantity()
    {
        $this->quantity++;
        $this->calculatePrice();
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->calculatePrice();
        }
    }

    public function proceedToCheckout()
    {
        $this->validate([
            'uploadedFiles' => 'required|array|min:1',
        ], [
            'uploadedFiles.required' => 'Silakan unggah desain/gambar yang akan disablon di tote bag.',
            'uploadedFiles.min' => 'Silakan unggah desain/gambar yang akan disablon di tote bag.',
        ]);

        $orderData = [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'product_name' => $this->product->name,
                    'quantity' => $this->quantity,
                    'unit_price' => $this->priceBreakdown['unit_price'],
                    'specifications' => [
                        'ukuran' => $this->selectedSize,
                        'warna' => $this->selectedColor,
                        'bahan' => $this->selectedMaterial,
                    ],
                    'subtotal' => $this->priceBreakdown['subtotal'],
                    'files' => $this->uploadedFiles,
                ]
            ],
            'subtotal' => $this->priceBreakdown['subtotal'],
            'total_price' => $this->calculatedPrice,
            'payment_method' => 'qris',
        ];

        $this->dispatch('proceed-to-checkout', $orderData);
    }

    public function render()
    {
        return view('livewire.forms.totebag-order-form');
    }
}
