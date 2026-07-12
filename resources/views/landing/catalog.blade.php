<x-layouts.app>
    <!-- Katalog Header -->
    <section class="w-full mx-auto px-6 lg:px-12 xl:px-20 py-16 md:py-24" id="katalog-header">
        <div class="text-center space-y-4">
            <h1 class="text-4xl md:text-6xl font-bold text-slate-900 dark:text-white tracking-tight">Katalog <span class="text-cyan-500">Produk & Jasa</span></h1>
            <p class="text-slate-600 dark:text-slate-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                Jelajahi berbagai pilihan layanan percetakan dan merchandise custom premium kami. Pesan sekarang dengan harga terjangkau!
            </p>
        </div>
    </section>

    <!-- Katalog Section -->
    <section class="pb-24 bg-transparent" id="katalog">
        <div class="w-full mx-auto px-6 lg:px-12 xl:px-20">
            
            <!-- Category Filters (Default Legacy Layout) -->
                <div class="flex flex-wrap items-center justify-center gap-3 mb-12" id="category-filters">
                    <a 
                        href="{{ route('landing.catalog') }}" 
                        class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 {{ !request('category') ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-md shadow-cyan-500/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-gray-300 border border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700' }}"
                    >
                        Semua
                    </a>
                    @foreach($categories as $category)
                        <a 
                            href="{{ route('landing.catalog', ['category' => $category->slug]) }}" 
                            class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 {{ request('category') === $category->slug ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-md shadow-cyan-500/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-gray-300 border border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700' }}"
                        >
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Products List / Empty State -->
                @if($products->isEmpty())
                    <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm" id="empty-catalog-state">
                        <div class="inline-flex p-4 rounded-full bg-cyan-50 dark:bg-cyan-900/30 text-cyan-500 mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-gray-100">Produk Tidak Ditemukan</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-2 max-w-sm mx-auto">
                            Maaf, saat ini layanan atau produk pada kategori ini belum tersedia. Silakan kembali beberapa saat lagi.
                        </p>
                        <a href="{{ route('landing.catalog') }}" class="inline-block mt-6 px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold rounded-xl text-sm shadow-md shadow-cyan-500/20 transition-all hover:shadow-lg">
                            Lihat Semua Produk
                        </a>
                    </div>
                @else
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($products as $product)
                            @php
                                $imgUrl = $product->image_url;
                                if (!$imgUrl) {
                                    // Map visual placeholders based on category slug if no image uploaded
                                    $imgUrl = 'https://images.unsplash.com/photo-1514228742587-6b1558fcf93a'; // Default
                                    if ($product->category->slug === 'jasa-print') {
                                        $imgUrl = 'https://images.unsplash.com/photo-1562408590-e32931084e23?auto=format&fit=crop&q=80&w=600';
                                    } elseif ($product->category->slug === 'jasa-fotocopy') {
                                        $imgUrl = 'https://images.unsplash.com/photo-1606857521015-7f9fcf423740?auto=format&fit=crop&q=80&w=600';
                                    } elseif ($product->category->slug === 'custom-mug') {
                                        $imgUrl = 'https://images.unsplash.com/photo-1514228742587-6b1558fcf93a?auto=format&fit=crop&q=80&w=600';
                                    } elseif ($product->category->slug === 'custom-kaos') {
                                        $imgUrl = 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&q=80&w=600';
                                    } elseif ($product->category->slug === 'custom-tote-bag') {
                                        $imgUrl = 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&q=80&w=600';
                                    }
                                }
                            @endphp
                            
                            <!-- Product Card -->
                            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-lg dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col hover:shadow-2xl hover:-translate-y-1 transition-all duration-300" id="product-card-{{ $product->id }}">
                                
                                <!-- Image Container -->
                                <div class="h-56 w-full relative overflow-hidden bg-gray-50 dark:bg-slate-900">
                                    <span class="absolute top-4 left-4 bg-blue-600/90 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider z-10 shadow-sm">
                                        {{ $product->category->name }}
                                    </span>
                                    <img 
                                        src="{{ $imgUrl }}" 
                                        alt="{{ $product->name }}" 
                                        class="w-full h-full object-cover"
                                    >
                                </div>
                                
                                <!-- Body -->
                                <div class="p-6 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-800 dark:text-gray-100 tracking-tight line-clamp-1">{{ $product->name }}</h3>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-2 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                                    </div>
                                    
                                    <div class="mt-6 space-y-4">
                                        <div class="flex items-baseline justify-between">
                                            <span class="text-slate-500 dark:text-slate-400 text-xs font-medium">Mulai dari</span>
                                            <span class="text-2xl font-bold text-blue-600 dark:text-cyan-400">
                                                Rp{{ number_format($product->base_price, 0, ',', '.') }}<span class="text-xs text-gray-400 dark:text-gray-500 font-normal">/{{ $product->unit }}</span>
                                            </span>
                                        </div>
                                        
                                        <a 
                                            href="{{ route('order.show', $product->slug) }}" 
                                            class="block w-full text-center bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white py-3.5 rounded-2xl font-semibold transition-all shadow-md shadow-cyan-500/30"
                                            id="btn-order-{{ $product->id }}"
                                        >
                                            Order Sekarang
                                        </a>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif
        </div>
    </section>
</x-layouts.app>
