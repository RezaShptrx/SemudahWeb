<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SEMUDAH - Solusi Percetakan & Custom Merchandise' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('SEMUDAH-LOGO-3-Favicon.png') }}">
    
    <!-- Meta SEO -->
    <meta name="description" content="SEMUDAH merupakan unit produksi SMKN 12 Jakarta yang menyediakan jasa percetakan dokumen, fotocopy, cetak kaos sablon custom, custom mug, dan tote bag premium berkualitas dengan harga terjangkau.">
    <meta name="keywords" content="cetak kaos, custom mug, cetak dokumen, fotocopy, tote bag kanvas, percetakan murah, smkn 12 jakarta">
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <!-- Dark Mode Init -->
    <script>
        if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#fafcff] dark:bg-slate-900 text-slate-800 dark:text-gray-100 font-sans antialiased transition-colors duration-300">

    <!-- Header / Navbar -->
    <header class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md shadow-sm dark:shadow-slate-900/50 sticky top-0 z-50 transition-colors duration-300 border-b border-transparent dark:border-slate-700">
        <nav class="w-full mx-auto px-6 lg:px-12 xl:px-20 py-5 flex items-center justify-between" id="main-navigation">
            
            <!-- Logo -->
            <a href="{{ route('landing.index') }}" class="flex items-center gap-3" id="logo-link">
                <img src="{{ asset('SEMUDAH-LOGO-3.png') }}" alt="SEMUDAH Logo" class="h-10 w-auto object-contain">
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center gap-8 text-base">
                <a href="{{ route('landing.index') }}#home" class="{{ request()->routeIs('landing.catalog') ? 'text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-cyan-400' : 'text-blue-600 dark:text-cyan-400 font-semibold hover:text-blue-700 dark:hover:text-cyan-300' }} transition-colors" id="nav-home">Home</a>
                <a href="{{ route('landing.index') }}#keunggulan" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-cyan-400 transition-colors" id="nav-features">Keunggulan</a>
                <a href="{{ route('landing.catalog') }}" class="{{ request()->routeIs('landing.catalog') ? 'text-blue-600 dark:text-cyan-400 font-semibold hover:text-blue-700 dark:hover:text-cyan-300' : 'text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-cyan-400' }} transition-colors" id="nav-catalog">Katalog</a>
                <a href="{{ route('landing.index') }}#faq" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-cyan-400 transition-colors" id="nav-faq">FAQ</a>
                <a href="https://wa.me/6281234567890" target="_blank" class="bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white px-6 py-2.5 rounded-xl font-semibold transition-all shadow-lg shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-0.5" id="btn-contact-us">
                    Contact Us
                </a>
                
                <!-- Dark Mode Toggle (Desktop) -->
                <div class="ml-2 border-l border-gray-200 dark:border-slate-700 pl-4 flex items-center">
                    <x-dark-mode-toggle />
                </div>
            </div>

            <!-- Mobile Hamburger Menu Button & Dark Mode Toggle -->
            <div class="md:hidden flex items-center gap-4" x-data="{ open: false }">
                <x-dark-mode-toggle />
                
                <button @click="open = !open" class="text-gray-600 dark:text-gray-300 hover:text-blue-800 dark:hover:text-cyan-400 focus:outline-none transition-colors" id="mobile-menu-toggle">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Mobile Dropdown -->
                <div x-cloak x-show="open" @click.away="open = false" x-transition class="absolute top-16 left-0 right-0 bg-white dark:bg-slate-800 shadow-md border-t dark:border-slate-700 px-6 py-4 flex flex-col gap-4 text-lg z-50">
                    <a href="{{ route('landing.index') }}#home" @click="open = false" class="{{ request()->routeIs('landing.catalog') ? 'text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-cyan-400' : 'text-blue-600 dark:text-cyan-400 font-semibold hover:text-blue-700 dark:hover:text-cyan-300' }} transition-colors" id="mobile-nav-home">Home</a>
                    <a href="{{ route('landing.index') }}#keunggulan" @click="open = false" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-cyan-400 transition-colors" id="mobile-nav-features">Keunggulan</a>
                    <a href="{{ route('landing.catalog') }}" @click="open = false" class="{{ request()->routeIs('landing.catalog') ? 'text-blue-600 dark:text-cyan-400 font-semibold hover:text-blue-700 dark:hover:text-cyan-300' : 'text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-cyan-400' }} transition-colors" id="mobile-nav-catalog">Katalog</a>
                    <a href="{{ route('landing.index') }}#faq" @click="open = false" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-cyan-400 transition-colors" id="mobile-nav-faq">FAQ</a>
                    <a href="https://wa.me/6281234567890" target="_blank" class="bg-gradient-to-r from-cyan-500 to-blue-500 text-white text-center py-2.5 rounded-xl font-semibold transition-colors" id="mobile-btn-contact-us">
                        Contact Us
                    </a>
                </div>
            </div>

        </nav>
    </header>

    <!-- Main Content -->
    <main id="main-content" class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white mt-20" id="main-footer">
        <div class="w-full mx-auto px-6 lg:px-12 xl:px-20 py-16 grid md:grid-cols-2 lg:grid-cols-4 gap-10">
            
            <div class="space-y-4">
                <h2 class="text-3xl font-bold tracking-wide">SEMUDAH</h2>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Unit produksi unggulan SMKN 12 Jakarta yang menghadirkan layanan cetak profesional, merchandise custom premium, dan solusi IT dengan harga terjangkau bagi pelajar dan masyarakat umum.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-lg mb-4 text-cyan-400">Layanan</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="{{ route('landing.catalog') }}" class="hover:text-white transition-colors">Jasa Print & Fotocopy</a></li>
                    <li><a href="{{ route('landing.catalog') }}" class="hover:text-white transition-colors">Cetak Mug Custom</a></li>
                    <li><a href="{{ route('landing.catalog') }}" class="hover:text-white transition-colors">Custom Kaos Sablon</a></li>
                    <li><a href="{{ route('landing.catalog') }}" class="hover:text-white transition-colors">Tote Bag Kanvas</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold text-lg mb-4 text-cyan-400">Navigasi</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="{{ route('landing.index') }}#home" class="hover:text-white transition-colors">Home</a></li>
                    <li><a href="{{ route('landing.index') }}#keunggulan" class="hover:text-white transition-colors">Keunggulan</a></li>
                    <li><a href="{{ route('landing.catalog') }}" class="hover:text-white transition-colors">Katalog Produk</a></li>
                    <li><a href="{{ route('landing.index') }}#faq" class="hover:text-white transition-colors">FAQ</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold text-lg mb-4 text-cyan-400">Kontak Kami</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li class="flex items-start gap-2">
                        <x-heroicon-o-map-pin class="w-5 h-5 text-cyan-500 shrink-0 mt-0.5" />
                        <span>Jl. Kebon Sirih No. 1, Jakarta Pusat</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <x-heroicon-o-phone class="w-5 h-5 text-cyan-500 shrink-0" />
                        <span>0812-3456-7890</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <x-heroicon-o-envelope class="w-5 h-5 text-cyan-500 shrink-0" />
                        <span>info@semudah.local</span>
                    </li>
                </ul>
            </div>

        </div>

        <hr class="border-slate-800">

        <div class="text-center py-8 text-gray-500 text-xs">
            © {{ date('Y') }} SEMUDAH. All Rights Reserved. Designed for SMKN 12 Jakarta.
        </div>
    </footer>

    @livewireScripts
</body>
</html>
