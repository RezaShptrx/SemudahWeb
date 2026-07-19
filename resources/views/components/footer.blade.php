<footer class="bg-semudah-anchor text-white mt-20" id="main-footer">
    <div class="w-full mx-auto px-6 lg:px-12 xl:px-20 py-16 grid md:grid-cols-2 lg:grid-cols-4 gap-10">
        
        <div class="space-y-4">
            <h2 class="font-heading text-3xl font-bold tracking-wide text-white">SEMUDAH</h2>
            <p class="text-white/80 text-sm leading-relaxed font-sans">
                Unit produksi unggulan SMKN 12 Jakarta yang menghadirkan layanan cetak profesional, merchandise custom premium, dan solusi IT dengan harga terjangkau bagi pelajar dan masyarakat umum.
            </p>
        </div>

        <div>
            <h3 class="font-heading font-semibold text-lg mb-4 text-semudah-accent">Layanan</h3>
            <ul class="space-y-2 text-white/80 text-sm font-sans">
                <li><a href="{{ route('landing.catalog') }}" class="hover:text-white transition-colors">Jasa Print & Fotocopy</a></li>
                <li><a href="{{ route('landing.catalog') }}" class="hover:text-white transition-colors">Cetak Mug Custom</a></li>
                <li><a href="{{ route('landing.catalog') }}" class="hover:text-white transition-colors">Custom Kaos Sablon</a></li>
                <li><a href="{{ route('landing.catalog') }}" class="hover:text-white transition-colors">Tote Bag Kanvas</a></li>
            </ul>
        </div>

        <div>
            <h3 class="font-heading font-semibold text-lg mb-4 text-semudah-accent">Navigasi</h3>
            <ul class="space-y-2 text-white/80 text-sm font-sans">
                <li><a href="{{ route('landing.index') }}#home" class="hover:text-white transition-colors">Home</a></li>
                <li><a href="{{ route('landing.index') }}#keunggulan" class="hover:text-white transition-colors">Keunggulan</a></li>
                <li><a href="{{ route('landing.catalog') }}" class="hover:text-white transition-colors">Katalog Produk</a></li>
                <li><a href="{{ route('landing.index') }}#faq" class="hover:text-white transition-colors">FAQ</a></li>
            </ul>
        </div>

        @php
            $siteSettings = \App\Models\Setting::whereIn('key', ['company_address', 'company_phone', 'company_email'])->pluck('value', 'key');
        @endphp

        <div>
            <h3 class="font-heading font-semibold text-lg mb-4 text-semudah-accent">Kontak Kami</h3>
            <ul class="space-y-2 text-white/80 text-sm font-sans">
                <li class="flex items-start gap-2">
                    <x-heroicon-o-map-pin class="w-5 h-5 text-semudah-accent shrink-0 mt-0.5" />
                    <span>{{ $siteSettings['company_address'] ?? 'Jl. Kebon Sirih No. 1, Jakarta Pusat' }}</span>
                </li>
                <li class="flex items-center gap-2">
                    <x-heroicon-o-phone class="w-5 h-5 text-semudah-accent shrink-0" />
                    <span>{{ $siteSettings['company_phone'] ?? '0812-3456-7890' }}</span>
                </li>
                <li class="flex items-center gap-2">
                    <x-heroicon-o-envelope class="w-5 h-5 text-semudah-accent shrink-0" />
                    <span>{{ $siteSettings['company_email'] ?? 'info@semudah.local' }}</span>
                </li>
            </ul>
        </div>

    </div>

    <hr class="border-white/10">

    <div class="text-center py-8 text-white/50 text-xs font-sans">
        © {{ date('Y') }} SEMUDAH. All Rights Reserved. Designed for SMKN 12 Jakarta.
    </div>
</footer>
