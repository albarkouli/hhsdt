<!DOCTYPE html>
<html lang="en" dir="ltr" class="dark">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page not found | WESALKOM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#17212B] text-gray-100 flex items-center justify-center p-4 font-sans">
    <div class="text-center max-w-lg">
        <div class="text-[120px] sm:text-[180px] font-black leading-none bg-gradient-to-br from-gold to-gold-hover bg-clip-text text-transparent">404</div>
        <h1 class="text-2xl sm:text-3xl font-black text-[#D6DEE6] mt-2 mb-3">Page not found</h1>
        <p class="text-[#A8B5C1] mb-8">The page you're looking for doesn't exist or has been moved.</p>
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="{{ url('/') }}" class="px-6 py-3 bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black rounded-xl text-sm shadow-[0_0_30px_rgba(163,230,53,0.25)]">Back to home</a>
            <a href="{{ url('/shop') }}" class="px-6 py-3 border-2 border-[#344453] hover:border-gold hover:text-gold text-[#D6DEE6] font-bold rounded-xl text-sm">Browse shop</a>
        </div>
    </div>
</body>
</html>