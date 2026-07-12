<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SEMUDAH</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('SEMUDAH-LOGO-3-Favicon.png') }}">
    
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 antialiased font-sans">
    <div id="react-root"></div>
</body>
</html>
