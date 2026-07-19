<header class="bg-semudah-bg dark:bg-slate-900 shadow-[0_4px_20px_rgba(0,0,0,0.04)] dark:shadow-slate-950/50 sticky top-0 z-50 transition-colors duration-300 border-b border-transparent dark:border-slate-800">
    <nav class="w-full mx-auto px-6 lg:px-12 xl:px-20 py-5 flex items-center justify-between" id="main-navigation">
        
        <!-- Logo -->
        <a href="{{ route('landing.index') }}" class="flex items-center gap-3" id="logo-link">
            <img src="{{ asset('SEMUDAH-LOGO-3.png') }}" alt="SEMUDAH Logo" class="h-10 w-auto object-contain">
        </a>

        @php
            $siteSettings = \App\Models\Setting::whereIn('key', ['company_address', 'company_phone', 'company_email', 'whatsapp_number'])->pluck('value', 'key');
        @endphp

        <!-- Desktop Navigation Links -->
        <div class="hidden md:flex items-center gap-8 text-base font-sans">
            <a href="{{ route('landing.index') }}#home" class="{{ request()->routeIs('landing.catalog') ? 'text-slate-600 dark:text-slate-300 hover:text-semudah-secondary dark:hover:text-semudah-accent' : 'text-semudah-primary dark:text-semudah-accent font-semibold hover:text-semudah-secondary dark:hover:text-cyan-300' }} transition-colors" id="nav-home">Home</a>
            <a href="{{ route('landing.index') }}#keunggulan" class="text-slate-600 dark:text-slate-300 hover:text-semudah-secondary dark:hover:text-semudah-accent transition-colors" id="nav-features">Keunggulan</a>
            <a href="{{ route('landing.catalog') }}" class="{{ request()->routeIs('landing.catalog') ? 'text-semudah-primary dark:text-semudah-accent font-semibold hover:text-semudah-secondary dark:hover:text-cyan-300' : 'text-slate-600 dark:text-slate-300 hover:text-semudah-secondary dark:hover:text-semudah-accent' }} transition-colors" id="nav-catalog">Katalog</a>
            <a href="{{ route('landing.index') }}#faq" class="text-slate-600 dark:text-slate-300 hover:text-semudah-secondary dark:hover:text-semudah-accent transition-colors" id="nav-faq">FAQ</a>
            <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '6281234567890' }}" target="_blank" class="bg-semudah-primary hover:bg-semudah-primary/90 dark:bg-semudah-secondary dark:hover:bg-semudah-secondary/90 text-white px-6 py-2.5 rounded-[8px] font-semibold transition-colors" id="btn-contact-us">
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
            
            <button @click="open = !open" class="text-semudah-primary dark:text-gray-300 hover:text-semudah-secondary dark:hover:text-semudah-accent focus:outline-none transition-colors" id="mobile-menu-toggle">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Mobile Dropdown -->
            <div x-cloak x-show="open" @click.away="open = false" x-transition class="absolute top-[80px] left-0 right-0 bg-semudah-bg dark:bg-slate-900 shadow-md border-t border-gray-100 dark:border-slate-800 px-6 py-4 flex flex-col gap-4 text-lg z-50 font-sans">
                <a href="{{ route('landing.index') }}#home" @click="open = false" class="{{ request()->routeIs('landing.catalog') ? 'text-slate-600 dark:text-slate-300 hover:text-semudah-secondary dark:hover:text-semudah-accent' : 'text-semudah-primary dark:text-semudah-accent font-semibold hover:text-semudah-secondary dark:hover:text-cyan-300' }} transition-colors" id="mobile-nav-home">Home</a>
                <a href="{{ route('landing.index') }}#keunggulan" @click="open = false" class="text-slate-600 dark:text-slate-300 hover:text-semudah-secondary dark:hover:text-semudah-accent transition-colors" id="mobile-nav-features">Keunggulan</a>
                <a href="{{ route('landing.catalog') }}" @click="open = false" class="{{ request()->routeIs('landing.catalog') ? 'text-semudah-primary dark:text-semudah-accent font-semibold hover:text-semudah-secondary dark:hover:text-cyan-300' : 'text-slate-600 dark:text-slate-300 hover:text-semudah-secondary dark:hover:text-semudah-accent' }} transition-colors" id="mobile-nav-catalog">Katalog</a>
                <a href="{{ route('landing.index') }}#faq" @click="open = false" class="text-slate-600 dark:text-slate-300 hover:text-semudah-secondary dark:hover:text-semudah-accent transition-colors" id="mobile-nav-faq">FAQ</a>
                <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '6281234567890' }}" target="_blank" class="bg-semudah-primary dark:bg-semudah-secondary text-white text-center py-2.5 rounded-[8px] font-semibold transition-colors" id="mobile-btn-contact-us">
                    Contact Us
                </a>
            </div>
        </div>
    </nav>
</header>
