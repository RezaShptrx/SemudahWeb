<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use App\Services\PriceCalculationService;
use Livewire\Component;
use Livewire\Attributes\On;

class PhotocopyOrderForm extends Component
{
    public $product;
    public $selectedSize = 'A4';
    public $selectedType = 'Tidak'; // Bolak balik
    public $quantity = 1; // Number of original pages
    public $copies = 1; // Number of copies per page
    public $notes = '';
    public $uploadedFiles = []; // Optional, maybe they scan it or we just assume they send files to be "photocopied" / printed as copy
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
            'ukuran_kertas' => $this->selectedSize,
            'bolak_balik' => $this->selectedType,
        ];

        // Quantity for pricing is usually total pages = original pages * copies
        $totalQuantity = $this->quantity * $this->copies;

        $this->priceBreakdown = app(PriceCalculationService::class)
            ->calculatePrice($this->product, $specs, $totalQuantity);

        $this->calculatedPrice = $this->priceBreakdown['final_price'];
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['selectedSize', 'selectedType', 'quantity', 'copies'])) {
            if ($propertyName === 'quantity' && $this->quantity < 1) $this->quantity = 1;
            if ($propertyName === 'copies' && $this->copies < 1) $this->copies = 1;
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

    public function incrementCopies()
    {
        $this->copies++;
        $this->calculatePrice();
    }

    public function decrementCopies()
    {
        if ($this->copies > 1) {
            $this->copies--;
            $this->calculatePrice();
        }
    }

    public function proceedToCheckout()
    {
        // For photocopy, files might be optional if they send the hardcopy offline, but let's make it optional here
        
        $totalQuantity = $this->quantity * $this->copies;

        $orderData = [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'product_name' => $this->product->name,
                    'quantity' => $totalQuantity, // Total billed pages
                    'unit_price' => $this->priceBreakdown['unit_price'],
                    'specifications' => [
                        'ukuran_kertas' => $this->selectedSize,
                        'bolak_balik' => $this->selectedType === 'Ya' ? 'Ya' : 'Tidak',
                        'jumlah_halaman_asli' => $this->quantity,
                        'jumlah_salinan' => $this->copies,
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
        return view('livewire.forms.photocopy-order-form');
    }
}
