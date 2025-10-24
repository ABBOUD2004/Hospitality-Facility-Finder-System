<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca&family=Montserrat:wght@400;500&family=Outfit&display=swap" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#F1E8D7] text-gray-900 font-['Lexend_Deca'] relative overflow-hidden min-h-screen flex flex-col">


<!-- زخرفة أسفل الصفحة كصورة -->
    <div class="absolute bottom-0 left-0 w-full pointer-events-none overflow-hidden ">
        <img src="{{ asset('images/footer-decoration.png') }}"
             alt="Footer Decoration"
             class="w-full object-cover">
    </div>
    <!-- المحتوى -->
    <div class="relative flex flex-col flex-grow justify-center items-center px-4 sm:px-6 lg:px-8 z-10">
        {{ $slot }}
    </div>

</body>
</html>
