<!DOCTYPE html>
<html lang="en" dir="ltr" class="dark">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server error | Libya Market</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#17212B] text-[#D6DEE6] flex items-center justify-center p-4 font-sans">
    <div class="text-center max-w-lg">
        <div class="text-[120px] sm:text-[180px] font-black leading-none bg-gradient-to-br from-red-500 to-orange-500 bg-clip-text text-transparent">500</div>
        <h1 class="text-2xl sm:text-3xl font-black text-[#D6DEE6] mt-2 mb-3">Something went wrong</h1>
        <p class="text-[#A8B5C1] mb-8">Our team has been notified. Please try again in a moment.</p>
        <a href="{{ url('/') }}" class="inline-block px-6 py-3 bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black rounded-xl text-sm shadow-[0_0_30px_rgba(163,230,53,0.25)]">Back to home</a>
    </div>
</body>
</html>