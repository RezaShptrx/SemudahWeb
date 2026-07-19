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
    
    <!-- Google Fonts: Plus Jakarta Sans & Source Sans 3 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400..800;1,400..800&family=Source+Sans+3:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
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
    <x-navbar />

    <!-- Main Content -->
    <main id="main-content" class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <x-footer />

    @livewireScripts
</body>
</html>
