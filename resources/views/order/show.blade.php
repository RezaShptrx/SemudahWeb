@if(empty($product->form_schema) && $product->category->slug === 'jasa-print')
    <x-layouts.transaction>
        <x-slot:title>
            Order {{ $product->name }} - SEMUDAH
        </x-slot:title>

        <div class="container-trans">
            <div class="header-trans">
                <a href="{{ route('landing.index') }}" class="logo-trans">
                    <img src="{{ asset('SEMUDAH-LOGO-3.png') }}" alt="SEMUDAH Logo">
                </a>
                <a href="{{ route('landing.index') }}#katalog" class="back-btn-trans">← Kembali</a>
            </div>
            
            @livewire('forms.print-order-form', ['product' => $product])
        </div>
    </x-layouts.transaction>
@else
    <x-layouts.app>
        <x-slot:title>
            Order {{ $product->name }} - SEMUDAH
        </x-slot:title>

        <div class="w-full mx-auto px-6 lg:px-12 xl:px-20 py-12" x-data="{ step: 'configure', orderData: null }">
            
            @if(empty($product->form_schema))
                <!-- Steps Indicator -->
                <div class="flex items-center justify-center gap-4 md:gap-8 mb-10">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm"
                             :class="step === 'configure' ? 'bg-blue-600 text-white' : 'bg-green-100 text-green-700'">
                            <span x-show="step === 'configure'">1</span>
                            <x-heroicon-o-check x-show="step === 'review'" class="w-4 h-4" />
                        </div>
                        <span class="font-medium text-sm md:text-base text-slate-800">Konfigurasi</span>
                    </div>
                    
                    <div class="h-0.5 w-12 md:w-20 bg-gray-200"></div>

                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm"
                             :class="step === 'review' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-400'">
                            2
                        </div>
                        <span class="font-medium text-sm md:text-base" :class="step === 'review' ? 'text-slate-800' : 'text-gray-400'">Review & Bayar</span>
                    </div>
                </div>
            @endif

            <!-- Product Header Info -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 mb-8 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between transition-colors">
                <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded-2xl shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 shrink-0">
                    @else
                        <div class="w-24 h-24 bg-cyan-50 dark:bg-cyan-900/30 rounded-2xl flex items-center justify-center text-cyan-200 dark:text-cyan-700 border border-cyan-100 dark:border-cyan-800 shrink-0">
                            <x-heroicon-o-photo class="w-10 h-10" />
                        </div>
                    @endif
                    <div class="space-y-2">
                        <span class="bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $product->category->name }}
                        </span>
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $product->name }}</h1>
                        <p class="text-gray-400 dark:text-slate-400 text-sm md:text-base max-w-xl leading-relaxed">{{ $product->description }}</p>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <span class="text-gray-400 dark:text-slate-400 text-xs block">Harga dasar</span>
                    <span class="text-2xl font-bold text-blue-600 dark:text-cyan-400">
                        Rp{{ number_format($product->base_price, 0, ',', '.') }}<span class="text-xs text-gray-400 dark:text-slate-500 font-normal">/{{ $product->unit }}</span>
                    </span>
                </div>
            </div>

            <!-- Step 1: Configure Form (Livewire) -->
            <div x-show="step === 'configure'">
                @if(!empty($product->form_schema))
                    @livewire('forms.dynamic-order-form', ['product' => $product])
                @elseif($product->category->slug === 'jasa-fotocopy')
                    @livewire('forms.photocopy-order-form', ['product' => $product])
                @elseif($product->category->slug === 'custom-mug')
                    @livewire('forms.mug-order-form', ['product' => $product])
                @elseif($product->category->slug === 'custom-kaos')
                    @livewire('forms.tshirt-order-form', ['product' => $product])
                @elseif($product->category->slug === 'custom-tote-bag')
                    @livewire('forms.totebag-order-form', ['product' => $product])
                @else
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-12 text-center border border-gray-100 dark:border-slate-700 shadow-sm dark:shadow-slate-900/50 space-y-4 transition-colors">
                        <div class="w-16 h-16 rounded-full bg-cyan-50 dark:bg-cyan-900/30 text-cyan-500 dark:text-cyan-400 flex items-center justify-center mx-auto">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl text-slate-800 dark:text-white">Formulir Sedang Disiapkan</h3>
                        <p class="text-gray-400 dark:text-slate-400 max-w-sm mx-auto text-sm">Formulir pemesanan khusus untuk produk ini sedang berada dalam antrean pengerjaan selanjutnya.</p>
                        <a href="{{ route('landing.index') }}#katalog" class="inline-block bg-blue-600 hover:bg-blue-700 dark:bg-cyan-600 dark:hover:bg-cyan-500 text-white px-6 py-2.5 rounded-xl font-semibold transition">Kembali ke Katalog</a>
                    </div>
                @endif
            </div>

            @if(empty($product->form_schema))
                <!-- Step 2: Review & Submit (Livewire) -->
                <div x-show="step === 'review'" x-cloak>
                    @livewire('forms.review-order-form')
                </div>
            @endif

        </div>

        <!-- AlpineJS event listener to transition between steps -->
        <script>
            document.addEventListener('alpine:init', () => {
                window.addEventListener('proceed-to-checkout', (event) => {
                    const data = event.detail[0];
                    // Dispatch event directly to ReviewOrderForm component
                    Livewire.dispatch('order-data-ready', { data: data });
                    // Update alpine state
                    Alpine.store('step', 'review');
                    document.querySelector('[x-data]').__x.$data.step = 'review';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
        </script>
    </x-layouts.app>
@endif
