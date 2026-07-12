<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use App\Services\PriceCalculationService;
use Livewire\Component;
use Livewire\Attributes\On;

class PrintOrderForm extends Component
{
    public $product;
    public $selectedColor = 'Hitam Putih';
    public $selectedSize = 'A4';
    public $selectedType = 'Tidak'; // Bolak balik
    public $selectedQuality = 'Standar';
    public $selectedPages = 'semua';
    public $customPageRange = '';
    public $quantity = 1;
    public $notes = '';
    public $uploadedFiles = [];
    public $calculatedPrice = 0;
    public $priceBreakdown = [];
    public $selectedPaymentMethod = 'qris';
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->calculatePrice();
    }

    #[On('files-uploaded')]
    public function handleFilesUploaded($files)
    {
        $this->uploadedFiles = array_merge($this->uploadedFiles, $files);
        $this->calculatePrice();
    }

    #[On('file-removed')]
    public function handleFileRemoved($index)
    {
        array_splice($this->uploadedFiles, $index, 1);
        $this->calculatePrice();
    }

    public function parseCustomPageRange(string $range, int $maxPages): int
    {
        if (empty($range)) {
            return $maxPages;
        }

        $pages = [];
        $parts = explode(',', $range);
        foreach ($parts as $part) {
            $part = trim($part);
            if (strpos($part, '-') !== false) {
                $subparts = explode('-', $part);
                if (count($subparts) === 2 && is_numeric($subparts[0]) && is_numeric($subparts[1])) {
                    $start = (int) $subparts[0];
                    $end = (int) $subparts[1];
                    $end = min($end, $maxPages);
                    for ($i = $start; $i <= $end; $i++) {
                        $pages[$i] = true;
                    }
                }
            } elseif (is_numeric($part)) {
                $val = (int) $part;
                if ($val > 0 && $val <= $maxPages) {
                    $pages[$val] = true;
                }
            }
        }

        return count($pages) > 0 ? count($pages) : $maxPages;
    }

    public function calculateTotalPages(): int
    {
        $totalPages = 0;
        foreach ($this->uploadedFiles as $file) {
            $maxPages = $file['page_count'] ?? 1;
            if ($this->selectedPages === 'custom') {
                $totalPages += $this->parseCustomPageRange($this->customPageRange, $maxPages);
            } else {
                $totalPages += $maxPages;
            }
        }
        return $totalPages;
    }

    public function calculatePrice()
    {
        $specs = [
            'warna' => $this->selectedColor,
            'kualitas' => $this->selectedQuality,
            'bolak_balik' => $this->selectedType,
        ];

        // Total pages based on uploaded files and range config
        $totalPages = $this->calculateTotalPages();
        // Fallback to 1 if no files uploaded yet, to show base price preview
        $totalPages = $totalPages > 0 ? $totalPages : 1;
        $totalSheets = $totalPages * $this->quantity;

        // Apply paper size modifier conceptually if needed, or query from DB.
        // For printing, base price includes standard. 
        // In our spec seeder, color and quality have modifiers.
        $this->priceBreakdown = app(PriceCalculationService::class)
            ->calculatePrice($this->product, $specs, $totalSheets);

        $this->calculatedPrice = $this->priceBreakdown['final_price'];
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['selectedColor', 'selectedSize', 'selectedType', 'selectedQuality', 'selectedPages', 'customPageRange', 'quantity'])) {
            if ($propertyName === 'quantity' && $this->quantity < 1) {
                $this->quantity = 1;
            }
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

    public function submitOrder()
    {
        $this->validate([
            'uploadedFiles' => 'required|array|min:1',
            'customPageRange' => $this->selectedPages === 'custom' ? 'required|string' : 'nullable',
            'customerName' => 'required|string|min:3',
            'customerPhone' => 'required|digits_between:10,15',
            'selectedPaymentMethod' => 'required|in:qris,tunai',
        ], [
            'uploadedFiles.required' => 'Silakan unggah dokumen yang akan dicetak terlebih dahulu.',
            'uploadedFiles.min' => 'Silakan unggah dokumen yang akan dicetak terlebih dahulu.',
            'customPageRange.required' => 'Harap masukkan halaman yang ingin dicetak.',
            'customerName.required' => 'Nama lengkap harus diisi.',
            'customerName.min' => 'Nama lengkap minimal 3 karakter.',
            'customerPhone.required' => 'Nomor WhatsApp harus diisi (10-15 angka).',
            'selectedPaymentMethod.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'selectedPaymentMethod.in' => 'Metode pembayaran tidak valid.',
        ]);

        $totalPages = $this->calculateTotalPages();
        $totalSheets = $totalPages * $this->quantity;

        $orderData = [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'product_name' => $this->product->name,
                    'quantity' => $totalSheets,
                    'unit_price' => $this->priceBreakdown['unit_price'],
                    'specifications' => [
                        'warna' => $this->selectedColor,
                        'ukuran_kertas' => $this->selectedSize,
                        'bolak_balik' => $this->selectedType === 'Ya' ? 'Ya' : 'Tidak',
                        'kualitas' => $this->selectedQuality,
                        'halaman' => $this->selectedPages === 'custom' ? $this->customPageRange : 'Semua Halaman',
                        'total_halaman' => $totalPages,
                        'jumlah_copy' => $this->quantity,
                    ],
                    'subtotal' => $this->priceBreakdown['subtotal'],
                    'files' => $this->uploadedFiles,
                ]
            ],
            'subtotal' => $this->priceBreakdown['subtotal'],
            'total_price' => $this->calculatedPrice,
            'payment_method' => strtolower($this->selectedPaymentMethod),
        ];

        // Save order and redirect
        try {
            $order = app(\App\Services\OrderService::class)->createOrder(
                customerName: $this->customerName ?: 'Guest',
                customerEmail: $this->customerEmail ?: 'guest@example.com',
                customerPhone: $this->customerPhone,
                items: $orderData['items'],
                totalPrice: $orderData['total_price'],
                paymentMethod: $orderData['payment_method'],
                notes: $this->notes
            );
            
            return redirect()->route('order.success', ['orderNumber' => $order->order_number]);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.forms.print-order-form');
    }
}
