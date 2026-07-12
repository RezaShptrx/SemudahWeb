<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;

class DynamicOrderForm extends Component
{
    public Product $product;
    public array $schema = [];
    
    // Form values
    public array $formData = [];
    public array $uploadedFiles = [];
    
    // Customer Info
    public string $customerName = '';
    public string $customerEmail = '';
    public string $customerPhone = '';
    public string $selectedPaymentMethod = 'qris';
    public string $notes = '';
    
    // Pricing
    public float $calculatedPrice = 0;
    
    public function mount(Product $product)
    {
        $this->product = $product;
        $this->schema = $product->form_schema ?? [];
        
        // Initialize default values based on schema
        foreach ($this->schema as $field) {
            $name = $field['name'];
            if ($field['type'] === 'number') {
                $this->formData[$name] = $field['min'] ?? 1;
            } elseif (in_array($field['type'], ['select', 'radio']) && !empty($field['options'])) {
                $this->formData[$name] = $field['options'][0]['value'];
            } elseif ($field['type'] === 'checkbox') {
                $this->formData[$name] = []; // Initialize as array for checkboxes
            } elseif ($field['type'] === 'color') {
                $this->formData[$name] = '#000000'; // Default color
            } elseif ($field['type'] === 'header') {
                // Headers don't have values
            } else {
                $this->formData[$name] = '';
            }
        }
        
        $this->calculatePrice();
    }
    
    #[On('files-uploaded')]
    public function handleFilesUploaded($files)
    {
        // $files is an array of uploaded files, we can tag them with the field name if needed,
        // but for simplicity, we'll store them globally in uploadedFiles
        $this->uploadedFiles = array_merge($this->uploadedFiles, $files);
        $this->calculatePrice();
    }

    #[On('file-removed')]
    public function handleFileRemoved($index)
    {
        array_splice($this->uploadedFiles, $index, 1);
        $this->calculatePrice();
    }

    public function updated($propertyName)
    {
        // Whenever any property changes (including nested formData), recalculate price
        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        $base = $this->product->base_price;
        $totalModifier = 0;
        $quantity = 1; // Default multiplier

        foreach ($this->schema as $field) {
            $name = $field['name'];
            $val = $this->formData[$name] ?? null;

            if (in_array($field['type'], ['select', 'radio']) && !empty($field['options']) && $val) {
                // Find the selected option to get its price_modifier
                $selectedOpt = collect($field['options'])->firstWhere('value', $val);
                if ($selectedOpt && isset($selectedOpt['price_modifier'])) {
                    $totalModifier += (float) $selectedOpt['price_modifier'];
                }
            }

            if ($field['type'] === 'checkbox' && !empty($field['options']) && is_array($val)) {
                // Sum modifiers for all selected checkboxes
                foreach ($val as $selectedVal) {
                    if ($selectedVal) { // Livewire checkboxes usually return true/false array map or selected values array
                        // if val is an array of selected values (e.g. ['opsi 1', 'opsi 2'])
                        $selectedOpt = collect($field['options'])->firstWhere('value', $selectedVal);
                        if ($selectedOpt && isset($selectedOpt['price_modifier'])) {
                            $totalModifier += (float) $selectedOpt['price_modifier'];
                        }
                    }
                }
            }

            // If there's a quantity field (often named qty or quantity), use it to multiply
            if ($field['type'] === 'number' && str_contains(strtolower($name), 'qty')) {
                $quantity = max(1, (int) $val);
            }
        }

        // For dynamic forms, final price = (base_price + modifiers) * quantity
        $this->calculatedPrice = ($base + $totalModifier) * $quantity;
    }

    public function submitOrder()
    {
        // Build validation rules dynamically
        $rules = [
            'customerName' => 'required|string|min:3',
            'customerPhone' => 'required|digits_between:10,15',
            'selectedPaymentMethod' => 'required|in:qris,tunai',
        ];
        
        $messages = [
            'customerName.required' => 'Nama lengkap wajib diisi.',
            'customerPhone.required' => 'Nomor WhatsApp wajib diisi (10-15 angka).',
        ];

        foreach ($this->schema as $field) {
            $name = $field['name'];
            $isRequired = $field['required'] ?? false;
            
            if ($field['type'] === 'file' && $isRequired) {
                $rules['uploadedFiles'] = 'required|array|min:1';
                $messages['uploadedFiles.required'] = 'Silakan unggah dokumen yang dibutuhkan.';
            } elseif ($field['type'] === 'header') {
                continue; // No validation for headers
            } else {
                if ($isRequired) {
                    $rules["formData.{$name}"] = 'required';
                    $messages["formData.{$name}.required"] = "Field {$field['label']} wajib diisi.";
                }
            }
        }

        $this->validate($rules, $messages);

        // Gather specs for order_items
        $specifications = [];
        foreach ($this->schema as $field) {
            if ($field['type'] !== 'file' && $field['type'] !== 'header') {
                $val = $this->formData[$field['name']] ?? '-';
                if (is_array($val)) {
                    // For checkboxes, filter out falsy values if it's an associative map, or just implode if it's a list
                    $val = implode(', ', array_filter($val, fn($v) => $v !== false && $v !== ''));
                }
                $specifications[$field['label']] = $val ?: '-';
            }
        }

        $orderData = [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'product_name' => $this->product->name,
                    // Use a generic quantity 1 if we don't know which field is qty
                    'quantity' => 1, 
                    'unit_price' => $this->calculatedPrice,
                    'specifications' => $specifications,
                    'subtotal' => $this->calculatedPrice,
                    'files' => $this->uploadedFiles,
                ]
            ],
            'subtotal' => $this->calculatedPrice,
            'total_price' => $this->calculatedPrice,
            'payment_method' => strtolower($this->selectedPaymentMethod),
        ];

        // Override quantity if we find a 'number' field containing 'qty'
        foreach ($this->schema as $field) {
            if ($field['type'] === 'number' && str_contains(strtolower($field['name']), 'qty')) {
                 $qty = (int) ($this->formData[$field['name']] ?? 1);
                 $orderData['items'][0]['quantity'] = $qty;
                 // Since unit_price is already calculatedPrice, we need to adjust
                 // Wait, calculatedPrice is ALREADY multiplied by qty in calculatePrice().
                 // So if quantity = 5, and total price is 5000, then unit_price should be 1000.
                 $orderData['items'][0]['unit_price'] = $qty > 0 ? $this->calculatedPrice / $qty : $this->calculatedPrice;
            }
        }

        // Tambahkan QRIS Admin Fee jika dipilih
        $finalTotalPrice = $orderData['total_price'];
        if (strtolower($this->selectedPaymentMethod) === 'qris') {
            $qrisFee = (float) (\App\Models\Setting::where('key', 'payment_qris_fee')->value('value') ?? 0);
            $finalTotalPrice += $qrisFee;
        }

        try {
            $order = app(\App\Services\OrderService::class)->createOrder(
                customerName: $this->customerName ?: 'Guest',
                customerEmail: $this->customerEmail ?: 'guest@example.com',
                customerPhone: $this->customerPhone,
                items: $orderData['items'],
                totalPrice: $finalTotalPrice,
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
        return view('livewire.forms.dynamic-order-form');
    }
}
