<x-layouts.app>
    
    <!-- Hero Section -->
    <section class="relative overflow-hidden min-h-[calc(100dvh-5rem)] flex items-center" id="home">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute top-[-10%] right-[-5%] w-96 h-96 bg-cyan-400/20 dark:bg-cyan-500/10 blur-3xl rounded-full"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-[30rem] h-[30rem] bg-blue-400/20 dark:bg-blue-600/10 blur-3xl rounded-full"></div>
        </div>

        <div class="w-full mx-auto px-6 lg:px-12 xl:px-20 py-12 md:py-20">
            <div class="grid lg:grid-cols-2 items-center gap-12 md:gap-16 z-10 relative">
            
            <!-- Left Info -->
            <div class="space-y-6 md:space-y-8 flex flex-col items-center lg:items-start text-center lg:text-left">
                <div class="inline-block px-4 py-1.5 bg-blue-50/80 dark:bg-blue-900/30 backdrop-blur-sm rounded-full text-blue-500 dark:text-cyan-400 text-sm font-semibold tracking-wide flex items-center justify-center lg:justify-start w-fit gap-1.5 border border-blue-100 dark:border-blue-800/50">
                    <x-heroicon-s-sparkles class="w-4 h-4" /> Welcome to SEMUDAH
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-[1.1] tracking-tight text-slate-900 dark:text-white">
                    <span class="text-cyan-500 dark:text-cyan-400">Semua Menjadi</span>
                    <br>
                    <span>Mudah</span>
                </h1>
                
                <p class="text-slate-600 dark:text-slate-400 text-lg lg:text-xl xl:text-2xl leading-relaxed max-w-lg lg:max-w-2xl mx-auto lg:mx-0">
                    Unit Produksi Unggulan SMKN 12 Jakarta menghadirkan jasa percetakan, merchandise custom premium, dan solusi teknologi berkualitas tinggi dengan harga yang sangat ramah kantong.
                </p>
                
                <div class="flex flex-col sm:flex-row flex-wrap gap-4 pt-2 justify-center lg:justify-start w-full sm:w-auto">
                    <a href="{{ route('landing.catalog') }}" class="bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white px-8 py-4 lg:px-10 lg:py-5 lg:text-xl rounded-2xl font-semibold transition-all shadow-lg shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-1 text-center w-full sm:w-auto" id="btn-hero-catalog">
                        Lihat Katalog
                    </a>
                    <a href="#keunggulan" class="bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm border-2 border-blue-200 dark:border-slate-700 text-blue-600 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 px-8 py-4 lg:px-10 lg:py-5 lg:text-xl rounded-2xl font-semibold transition-all text-center w-full sm:w-auto hover:-translate-y-1" id="btn-hero-features">
                        Tentang Kami
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 md:gap-10 pt-10 border-t border-gray-100 dark:border-slate-800 w-full">
                    <div class="flex flex-col items-center lg:items-start text-center lg:text-left">
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-blue-600 dark:text-cyan-400">500+</h2>
                        <p class="text-slate-600 dark:text-slate-400 text-xs md:text-sm lg:text-base mt-1 lg:mt-2 font-medium">Pelanggan Puas</p>
                    </div>
                    <div class="flex flex-col items-center lg:items-start text-center lg:text-left">
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-blue-600 dark:text-cyan-400">10+</h2>
                        <p class="text-slate-600 dark:text-slate-400 text-xs md:text-sm lg:text-base mt-1 lg:mt-2 font-medium">Layanan Utama</p>
                    </div>
                    <div class="flex flex-col items-center lg:items-start text-center lg:text-left">
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-blue-600 dark:text-cyan-400">100%</h2>
                        <p class="text-slate-600 dark:text-slate-400 text-xs md:text-sm lg:text-base mt-1 lg:mt-2 font-medium">Garansi Mutu</p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-center lg:justify-end">
                <img 
                    src="{{ asset('Semecha.png') }}" 
                    alt="Semecha Robot" 
                    class="w-[90%] max-w-[500px] lg:max-w-[650px] xl:max-w-[800px] object-contain"
                    id="hero-illustration-img"
                >
            </div>

        </div>
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section class="py-20 md:py-24 bg-gray-50/50 dark:bg-slate-900/50 border-y border-gray-100 dark:border-slate-800" id="keunggulan">
        <div class="w-full mx-auto px-6 lg:px-12 xl:px-20">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <div class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-50/80 dark:bg-blue-900/30 backdrop-blur-sm rounded-full text-blue-500 dark:text-cyan-400 text-xs font-semibold tracking-wide border border-blue-100 dark:border-blue-800/50">Mengapa Memilih Kami</div>
                <h2 class="text-3xl md:text-5xl font-bold text-slate-900 dark:text-white tracking-tight">Keunggulan SEMUDAH</h2>
            </div>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 mt-16">
                
                <!-- Card 1 -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 dark:bg-slate-700 flex items-center justify-center text-cyan-500 dark:text-cyan-400 group-hover:bg-gradient-to-br group-hover:from-cyan-500 group-hover:to-blue-500 group-hover:text-white transition duration-300 shadow-sm">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl text-slate-800 dark:text-gray-100 mt-6 group-hover:text-cyan-500 dark:group-hover:text-cyan-400 transition-colors">Proses Cepat</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-3 leading-relaxed">Setiap pesanan Anda akan segera diproses secara tanggap dan tepat waktu.</p>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 dark:bg-slate-700 flex items-center justify-center text-cyan-500 dark:text-cyan-400 group-hover:bg-gradient-to-br group-hover:from-cyan-500 group-hover:to-blue-500 group-hover:text-white transition duration-300 shadow-sm">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl text-slate-800 dark:text-gray-100 mt-6 group-hover:text-cyan-500 dark:group-hover:text-cyan-400 transition-colors">Sangat Berkualitas</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-3 leading-relaxed">Didukung oleh bahan pilihan dan tinta berkualitas untuk menjamin mutu.</p>
                </div>
                
                <!-- Card 3 -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 dark:bg-slate-700 flex items-center justify-center text-cyan-500 dark:text-cyan-400 group-hover:bg-gradient-to-br group-hover:from-cyan-500 group-hover:to-blue-500 group-hover:text-white transition duration-300 shadow-sm">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl text-slate-800 dark:text-gray-100 mt-6 group-hover:text-cyan-500 dark:group-hover:text-cyan-400 transition-colors">Harga Bersahabat</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-3 leading-relaxed">Tarif yang fleksibel dan terjangkau bagi kalangan siswa maupun instansi.</p>
                </div>
                
                <!-- Card 4 -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 dark:bg-slate-700 flex items-center justify-center text-cyan-500 dark:text-cyan-400 group-hover:bg-gradient-to-br group-hover:from-cyan-500 group-hover:to-blue-500 group-hover:text-white transition duration-300 shadow-sm">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl text-slate-800 dark:text-gray-100 mt-6 group-hover:text-cyan-500 dark:group-hover:text-cyan-400 transition-colors">Pelayanan Prima</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-3 leading-relaxed">Dilayani ramah oleh tim profesional siap konsultasi kebutuhan Anda.</p>
                </div>

            </div>

        </div>
    </section>

    <!-- Note: Katalog section was moved to its own page -->

    <!-- Testimoni Section -->
    <section class="py-20 md:py-24 bg-white dark:bg-slate-900 border-t border-gray-100 dark:border-slate-800" id="testimoni">
        <div class="w-full mx-auto px-6 lg:px-12 xl:px-20">
            
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-16">
                <div class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-50/80 dark:bg-blue-900/30 backdrop-blur-sm rounded-full text-blue-500 dark:text-cyan-400 text-xs font-semibold tracking-wide border border-blue-100 dark:border-blue-800/50">Testimoni</div>
                <h2 class="text-3xl md:text-5xl font-bold text-slate-900 dark:text-white tracking-tight">Apa Kata Mereka?</h2>
            </div>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimoni 1 -->
                <div class="bg-gray-50 dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700">
                    <div class="flex gap-1 text-yellow-400 mb-4">
                        <x-heroicon-s-star class="w-5 h-5" />
                        <x-heroicon-s-star class="w-5 h-5" />
                        <x-heroicon-s-star class="w-5 h-5" />
                        <x-heroicon-s-star class="w-5 h-5" />
                        <x-heroicon-s-star class="w-5 h-5" />
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 italic mb-6">"Pengerjaannya cepat dan harganya terjangkau"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-cyan-100 dark:bg-cyan-900 flex items-center justify-center text-cyan-600 dark:text-cyan-300 font-bold">I</div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">Ibnu Abi Ad-Dunya</p>
                            <p class="text-slate-500 dark:text-slate-400 text-xs">Siswa SMKN 12</p>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 2 -->
                <div class="bg-gray-50 dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700">
                    <div class="flex gap-1 text-yellow-400 mb-4">
                        <x-heroicon-s-star class="w-5 h-5" />
                        <x-heroicon-s-star class="w-5 h-5" />
                        <x-heroicon-s-star class="w-5 h-5" />
                        <x-heroicon-s-star class="w-5 h-5" />
                        <x-heroicon-s-star class="w-5 h-5" />
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 italic mb-6">"Pelayanan nya sangat bagus dan memuaskan."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 font-bold">I</div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">Ichsan Riandi Putra</p>
                            <p class="text-slate-500 dark:text-slate-400 text-xs">Osis SMKN 12</p>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 3 -->
                <div class="bg-gray-50 dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700">
                    <div class="flex gap-1 text-yellow-400 mb-4">
                        <x-heroicon-s-star class="w-5 h-5" />
                        <x-heroicon-s-star class="w-5 h-5" />
                        <x-heroicon-s-star class="w-5 h-5" />
                        <x-heroicon-s-star class="w-5 h-5" />
                        <x-heroicon-s-star class="w-5 h-5" />
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 italic mb-6">"Pengerjaannya sangat terampil"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold">A</div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">Ahmad Fauzan</p>
                            <p class="text-slate-500 dark:text-slate-400 text-xs">Siswa SMKN 12</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 md:py-24 bg-transparent border-t border-gray-100 dark:border-slate-800" id="faq">
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            
            <div class="text-center space-y-3 mb-16">
                <div class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-50/80 dark:bg-blue-900/30 backdrop-blur-sm rounded-full text-blue-500 dark:text-cyan-400 text-xs font-semibold tracking-wide border border-blue-100 dark:border-blue-800/50">FAQ</div>
                <h2 class="text-3xl md:text-5xl font-bold text-slate-900 dark:text-white tracking-tight">Pertanyaan yang Sering Diajukan</h2>
            </div>
            
            <!-- Accordion (AlpineJS) -->
            <div class="space-y-4" x-data="{ active: null }">
                
                <!-- Q1 -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
                    <button 
                        @click="active === 1 ? active = null : active = 1" 
                        class="w-full text-left px-6 py-5 font-bold text-slate-800 dark:text-gray-100 text-base md:text-lg flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors"
                        id="faq-toggle-1"
                    >
                        <span>Apakah bisa memesan tanpa minimal order?</span>
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 transition duration-300" :class="{'rotate-180 text-cyan-500': active === 1}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 1" x-collapse class="px-6 pb-5 text-sm md:text-base text-slate-500 dark:text-slate-400 leading-relaxed">
                        Ya, untuk produk custom seperti Mug dan Kaos Sablon, Anda bisa melakukan pemesanan satuan (tanpa minimal order) dengan harga bersahabat.
                    </div>
                </div>

                <!-- Q2 -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
                    <button 
                        @click="active === 2 ? active = null : active = 2" 
                        class="w-full text-left px-6 py-5 font-bold text-slate-800 dark:text-gray-100 text-base md:text-lg flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors"
                        id="faq-toggle-2"
                    >
                        <span>Berapa lama estimasi pengerjaan pesanan?</span>
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 transition duration-300" :class="{'rotate-180 text-cyan-500': active === 2}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 2" x-collapse class="px-6 pb-5 text-sm md:text-base text-slate-500 dark:text-slate-400 leading-relaxed">
                        Untuk jasa cetak dokumen biasa dan fotocopy berkisar beberapa menit hingga beberapa jam (tergantung antrean). Untuk produk custom merchandise seperti Kaos dan Mug berkisar 1 - 3 hari kerja setelah desain disetujui.
                    </div>
                </div>

                <!-- Q3 -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
                    <button 
                        @click="active === 3 ? active = null : active = 3" 
                        class="w-full text-left px-6 py-5 font-bold text-slate-800 dark:text-gray-100 text-base md:text-lg flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors"
                        id="faq-toggle-3"
                    >
                        <span>Bagaimana cara melakukan pembayaran?</span>
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 transition duration-300" :class="{'rotate-180 text-cyan-500': active === 3}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 3" x-collapse class="px-6 pb-5 text-sm md:text-base text-slate-500 dark:text-slate-400 leading-relaxed">
                        Kami menerima pembayaran non-tunai secara otomatis menggunakan QRIS/E-Wallet (Gopay, ShopeePay) serta pembayaran langsung secara tunai/cash di kasir outlet kami.
                    </div>
                </div>

            </div>
            
        </div>
    </section>

</x-layouts.app>
