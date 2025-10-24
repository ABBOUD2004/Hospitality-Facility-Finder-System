<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HF Finder') }} - @yield('title', 'Find Hospitality Facilities')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind + Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 50%;
            background-color: #F46A06;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .smooth-scroll {
            scroll-behavior: smooth;
        }
    </style>

    @stack('styles')
</head>
<body class="font-[Inter] bg-gray-50 antialiased smooth-scroll">

    <!-- Navbar -->
    <nav class="w-full bg-white/95 backdrop-blur-sm shadow-sm fixed top-0 left-0 z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center space-x-1 select-none group">
                    <span class="text-2xl sm:text-3xl font-bold text-black group-hover:scale-105 transition-transform">HF</span>
                    <span class="text-lg sm:text-xl font-semibold text-gray-800">finder</span>
                    <span class="text-[#F46A06] text-xl sm:text-2xl font-bold animate-pulse">.</span>
                </a>

                <!-- Desktop Menu -->
                <ul class="hidden md:flex items-center space-x-8 lg:space-x-10">
                    <li>
                        <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }} flex items-center font-semibold text-base lg:text-lg text-gray-700 hover:text-[#F46A06] transition-colors">
                            <i class="fa-solid fa-house mr-2 text-[#F46A06]"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/contact') }}" class="nav-link {{ request()->is('contact') ? 'active' : '' }} flex items-center font-semibold text-base lg:text-lg text-gray-700 hover:text-[#F46A06] transition-colors">
                            <i class="fa-solid fa-phone mr-2 text-[#F46A06]"></i>
                            Contact
                        </a>
                    </li>
                    @guest
                        <li>
                            <a href="{{ route('login') }}" class="flex items-center font-semibold text-base lg:text-lg bg-[#F46A06] text-white px-6 py-2.5 rounded-full hover:bg-[#d85e05] hover:shadow-lg transition-all duration-300">
                                <i class="fa-solid fa-right-to-bracket mr-2"></i>
                                Login
                            </a>
                        </li>
                    @else
                        <li class="relative group">
                            <button class="flex items-center space-x-2 font-semibold text-base lg:text-lg text-gray-700 hover:text-[#F46A06] transition-colors">
                                <i class="fa-solid fa-user-circle text-2xl"></i>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 py-2">
                                <a href="{{ url('/dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fa-solid fa-gauge mr-2"></i> Dashboard
                                </a>
                                <a href="{{ url('/profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fa-solid fa-user mr-2"></i> Profile
                                </a>
                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>

                <!-- Mobile Menu Button -->
                <button id="menu-btn" class="block md:hidden text-2xl text-[#F46A06] focus:outline-none hover:scale-110 transition-transform" aria-label="Toggle menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg rounded-b-2xl mt-4 overflow-hidden">
                <ul class="flex flex-col divide-y divide-gray-100">
                    <li>
                        <a href="{{ url('/') }}" class="flex items-center font-semibold text-gray-700 hover:bg-gray-50 hover:text-[#F46A06] transition-colors px-6 py-4">
                            <i class="fa-solid fa-house mr-3 text-[#F46A06]"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/contact') }}" class="flex items-center font-semibold text-gray-700 hover:bg-gray-50 hover:text-[#F46A06] transition-colors px-6 py-4">
                            <i class="fa-solid fa-phone mr-3 text-[#F46A06]"></i>
                            Contact
                        </a>
                    </li>
                    @guest
                        <li>
                            <a href="{{ route('login') }}" class="flex items-center font-semibold text-[#F46A06] hover:bg-gray-50 transition-colors px-6 py-4">
                                <i class="fa-solid fa-right-to-bracket mr-3"></i>
                                Login
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ url('/dashboard') }}" class="flex items-center font-semibold text-gray-700 hover:bg-gray-50 hover:text-[#F46A06] transition-colors px-6 py-4">
                                <i class="fa-solid fa-gauge mr-3 text-[#F46A06]"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/profile') }}" class="flex items-center font-semibold text-gray-700 hover:bg-gray-50 hover:text-[#F46A06] transition-colors px-6 py-4">
                                <i class="fa-solid fa-user mr-3 text-[#F46A06]"></i>
                                Profile
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center font-semibold text-red-600 hover:bg-red-50 transition-colors px-6 py-4">
                                    <i class="fa-solid fa-right-from-bracket mr-3"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div data-success-message="{{ session('success') }}"></div>
    @endif

    @if(session('error'))
        <div data-error-message="{{ session('error') }}"></div>
    @endif

    <!-- Page Content -->
    <main class="pt-20 sm:pt-24 min-h-screen">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                const icon = menuBtn.querySelector('i');
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!menuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                    const icon = menuBtn.querySelector('i');
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                }
            });
        }

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
