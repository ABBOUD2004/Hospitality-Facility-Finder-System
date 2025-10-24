@extends('layouts.app')

@section('content')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&family=Sedan+SC&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            overflow-x: hidden;
        }
        .sedan-font { font-family: 'Sedan SC', serif; }
        .match-orange { background: #F1E8D7; }
        .primary-orange { background: #F46A06; }
        .text-orange { color: #F46A06; }

        /* =============== Enhanced Service Icon =============== */
        .service-icon {
            background: linear-gradient(135deg, #F46A06 0%, #ff8c42 100%);
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            color: white;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(244, 106, 6, 0.3);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .service-icon:hover {
            transform: rotate(10deg) scale(1.15);
            box-shadow: 0 8px 25px rgba(244, 106, 6, 0.5);
        }

        /* =============== Smooth Scrolling =============== */
        html {
            scroll-behavior: smooth;
        }

        /* =============== Custom Scrollbar =============== */
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #F46A06, #ff8c42);
            border-radius: 10px;
            border: 2px solid #f1f1f1;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #d85a05, #F46A06);
        }

        /* =============== Professional Animations =============== */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.85);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.15);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* =============== Notification Animations =============== */
        .notification {
            animation: slideInRight 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .notification.fade-out {
            animation: slideInRight 0.3s ease-out reverse;
        }

        /* =============== Modal Animations =============== */
        .modal-backdrop {
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            animation: fadeInScale 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* =============== Cart Badge Animation =============== */
        .cart-badge {
            animation: pulse 0.6s ease-in-out;
        }

        /* =============== Menu Item Hover Effects =============== */
        .menu-item {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-origin: center;
        }

        .menu-item:hover {
            transform: translateY(-12px) scale(1.03);
            box-shadow: 0 25px 50px rgba(244, 106, 6, 0.25);
        }

        .menu-item img {
            transition: transform 0.5s ease;
        }

        .menu-item:hover img {
            transform: scale(1.1);
        }

        /* =============== Category Button Effects =============== */
        .category-btn {
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
            overflow: hidden;
        }

        .category-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: translate(-50%, -50%);
            transition: width 0.5s, height 0.5s;
        }

        .category-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .category-btn.active {
            background: white !important;
            color: #F46A06 !important;
            box-shadow: 0 5px 20px rgba(244, 106, 6, 0.4);
            transform: translateY(-3px);
        }

        .category-btn:hover:not(.active) {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.6);
        }

        /* =============== Gallery Hover Effects =============== */
        .gallery-item {
            overflow: hidden;
            position: relative;
        }

        .gallery-item img {
            transition: transform 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .gallery-item:hover img {
            transform: scale(1.2) rotate(3deg);
        }

        .gallery-overlay {
            transition: opacity 0.4s ease;
        }

        /* =============== Hero Image Effects =============== */
        .hero-container {
            animation: slideInUp 0.8s ease-out;
        }

        .hero-image {
            transition: transform 0.7s ease;
        }

        .hero-container:hover .hero-image {
            transform: scale(1.08);
        }

        /* =============== Card Entrance Animations =============== */
        .fade-in-up {
            animation: slideInUp 0.6s ease-out;
        }

        .fade-in-left {
            animation: slideInLeft 0.6s ease-out;
        }

        .fade-in-right {
            animation: slideInRight 0.6s ease-out;
        }

        /* =============== Loading Spinner =============== */
        .spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* =============== Button Effects =============== */
        .btn-hover {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-hover::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-hover:hover::after {
            width: 400px;
            height: 400px;
        }

        .btn-hover:active {
            transform: scale(0.95);
        }

        /* =============== Floating Animation =============== */
        .floating {
            animation: float 3s ease-in-out infinite;
        }

        /* =============== Bouncing Animation =============== */
        .bouncing {
            animation: bounce 2s ease-in-out infinite;
        }

        /* =============== Shimmer Effect =============== */
        .shimmer {
            background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }

        /* =============== Service Card Stagger Animation =============== */
        .service-card {
            opacity: 0;
            animation: slideInUp 0.6s ease-out forwards;
        }

        .service-card:nth-child(1) { animation-delay: 0.1s; }
        .service-card:nth-child(2) { animation-delay: 0.2s; }
        .service-card:nth-child(3) { animation-delay: 0.3s; }
        .service-card:nth-child(4) { animation-delay: 0.4s; }
        .service-card:nth-child(5) { animation-delay: 0.5s; }
        .service-card:nth-child(6) { animation-delay: 0.6s; }

        /* =============== Menu Item Stagger Animation =============== */
        .menu-item {
            opacity: 0;
            animation: fadeInScale 0.5s ease-out forwards;
        }

        .menu-item:nth-child(1) { animation-delay: 0.1s; }
        .menu-item:nth-child(2) { animation-delay: 0.15s; }
        .menu-item:nth-child(3) { animation-delay: 0.2s; }
        .menu-item:nth-child(4) { animation-delay: 0.25s; }
        .menu-item:nth-child(5) { animation-delay: 0.3s; }
        .menu-item:nth-child(6) { animation-delay: 0.35s; }
        .menu-item:nth-child(7) { animation-delay: 0.4s; }
        .menu-item:nth-child(8) { animation-delay: 0.45s; }
        .menu-item:nth-child(9) { animation-delay: 0.5s; }
    </style>

    <!-- Hero Image -->
    <section class="relative w-full px-4 sm:px-6 lg:px-8 pt-6">
        <div class="hero-container relative h-[280px] sm:h-[350px] md:h-[420px] lg:h-[480px] overflow-hidden rounded-3xl border-8 border-orange-100 shadow-2xl">
            @if($facility->image && file_exists(public_path('storage/' . $facility->image)))
                <img src="{{ asset('storage/' . $facility->image) }}"
                     alt="{{ $facility->name }}"
                     class="hero-image w-full h-full object-cover"
                     onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
            @elseif($facility->image && file_exists(public_path($facility->image)))
                <img src="{{ asset($facility->image) }}"
                     alt="{{ $facility->name }}"
                     class="hero-image w-full h-full object-cover"
                     onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
            @else
                <div class="w-full h-full bg-gradient-to-br from-orange-200 to-orange-400 flex items-center justify-center">
                    <i class="fas fa-coffee text-white floating" style="font-size: 120px; opacity: 0.5;"></i>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 bg-black/70 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20 transform hover:scale-105 transition-all duration-300">
                <p class="text-xs sm:text-sm text-white/90 mb-1 flex items-center gap-2">
                    <i class="fas fa-tag"></i> Average price
                </p>
                <p class="text-lg sm:text-2xl font-bold text-white">{{ number_format($facility->menuItems->avg('price') ?? 7000) }} RWF</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <!-- Facility Info Card -->
        <div class="fade-in-up match-orange rounded-3xl shadow-2xl p-8 sm:p-12 mb-16 -mt-32 relative z-10 hover:shadow-3xl transition-all duration-500">
            <div class="flex flex-wrap justify-between items-start gap-6 mb-6">
                <div class="flex-1">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-3 text-gray-900">{{ $facility->name }}</h2>
                    <div class="flex items-center gap-2 mb-3">
                        @for($i = 0; $i < 4; $i++)
                            <i class="fas fa-star text-orange text-xl"></i>
                        @endfor
                        <i class="far fa-star text-orange text-xl"></i>
                        <span class="text-lg ml-2 text-gray-700 font-semibold">4.0 Rating</span>
                    </div>
                    <div class="flex flex-wrap gap-4 text-gray-700">
                        <span class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <i class="fas fa-map-marker-alt text-orange"></i>
                            <span class="font-medium">{{ $facility->city ?? 'Kigali' }}</span>
                        </span>
                        <span class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <i class="fas fa-chair text-orange"></i>
                            <span class="font-medium">{{ $facility->capacity ?? '50' }} Seats</span>
                        </span>
                        <span class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <i class="fas fa-phone text-orange"></i>
                            <span class="font-medium">{{ $facility->contact ?? 'Contact Available' }}</span>
                        </span>
                    </div>
                </div>
                <a href="{{ route('coffee-shops') }}" class="btn-hover bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2 shadow-lg hover:shadow-2xl">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <hr class="border-orange-300 border-2 mb-6 opacity-50">

            <h3 class="text-2xl sm:text-3xl font-bold text-orange mb-4 flex items-center gap-3">
                <i class="fas fa-info-circle"></i> Coffee Shop Description
            </h3>

            <p class="sedan-font text-lg sm:text-xl leading-relaxed text-gray-800">
                {{ $facility->description ?? 'Welcome to our coffee shop. Experience the finest coffee and cozy atmosphere in a relaxing environment perfect for meetings, work, or leisure.' }}
            </p>
        </div>

        <!-- Services Section -->
        <div class="mb-16 fade-in-left">
            <h3 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i class="fas fa-concierge-bell text-orange bouncing"></i> Services We Offer
            </h3>
            <hr class="border-gray-300 border-2 mb-12">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $serviceIcons = [
                        'Outside catering' => 'fa-utensils',
                        'Birthday ceremonies' => 'fa-birthday-cake',
                        'Smoking area' => 'fa-smoking',
                        'Free wifi' => 'fa-wifi',
                        'Free WiFi' => 'fa-wifi',
                        'Meetings' => 'fa-handshake',
                        'Engagement events' => 'fa-ring',
                        'Parking' => 'fa-parking',
                        'Live Music' => 'fa-music',
                        'Outdoor Seating' => 'fa-tree',
                        'Takeaway' => 'fa-shopping-bag',
                        'Delivery' => 'fa-truck',
                        'Air Conditioning' => 'fa-snowflake',
                        'Pet Friendly' => 'fa-paw',
                    ];
                @endphp
                @forelse($facility->services as $service)
                    <div class="service-card bg-white border-2 border-orange-200 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group">
                        <div class="flex items-center gap-4">
                            <div class="service-icon">
                                <i class="fas {{ $serviceIcons[$service->name] ?? 'fa-check-circle' }}"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">{{ $service->name }}</h4>
                                <p class="text-sm text-gray-600">Available for you</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-8">
                        <i class="fas fa-info-circle text-4xl mb-3 opacity-30"></i>
                        <p>No services available</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Explore Menu Button -->
        <div class="text-center mb-12 fade-in-up">
            <button onclick="document.getElementById('menu-section').scrollIntoView({behavior: 'smooth'})"
                    class="btn-hover primary-orange text-white text-2xl sm:text-3xl font-bold px-12 sm:px-16 py-5 sm:py-6 rounded-2xl shadow-2xl hover:bg-orange-600 transition-all duration-300 transform hover:scale-105 flex items-center gap-3 mx-auto">
                <i class="fas fa-book-open"></i> Explore Our Menu
            </button>
        </div>

        <!-- Menu Section -->
        <div id="menu-section" class="mb-16 fade-in-right scroll-mt-24">
            <h3 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i class="fas fa-clipboard-list text-orange"></i> Our Menu
            </h3>
            <hr class="border-gray-300 border-2 mb-8">

            <!-- Category Filter -->
            <div class="match-orange rounded-xl p-4 mb-8 flex flex-wrap justify-center gap-3 shadow-xl">
                <button onclick="filterMenu('all')"
                        class="category-btn active px-4 sm:px-5 py-2 sm:py-3 rounded-lg font-bold text-base sm:text-lg flex items-center gap-2 relative z-10">
                    <i class="fas fa-th-large"></i> <span class="hidden sm:inline">All</span>
                </button>
                <button onclick="filterMenu('Coffee drinks')"
                        class="category-btn px-4 sm:px-5 py-2 sm:py-3 rounded-lg font-bold text-base sm:text-lg flex items-center gap-2 relative z-10">
                    <i class="fas fa-mug-hot"></i> <span class="hidden sm:inline">Coffee</span>
                </button>
                <button onclick="filterMenu('Main dishes')"
                        class="category-btn px-4 sm:px-5 py-2 sm:py-3 rounded-lg font-bold text-base sm:text-lg flex items-center gap-2 relative z-10">
                    <i class="fas fa-hamburger"></i> <span class="hidden sm:inline">Main Dishes</span>
                </button>
                <button onclick="filterMenu('Snacks')"
                        class="category-btn px-4 sm:px-5 py-2 sm:py-3 rounded-lg font-bold text-base sm:text-lg flex items-center gap-2 relative z-10">
                    <i class="fas fa-cookie"></i> <span class="hidden sm:inline">Snacks</span>
                </button>
                <button onclick="filterMenu('Soft drinks')"
                        class="category-btn px-4 sm:px-5 py-2 sm:py-3 rounded-lg font-bold text-base sm:text-lg flex items-center gap-2 relative z-10">
                    <i class="fas fa-glass-whiskey"></i> <span class="hidden sm:inline">Soft Drinks</span>
                </button>
                <button onclick="filterMenu('Alcoholic drinks')"
                        class="category-btn px-4 sm:px-5 py-2 sm:py-3 rounded-lg font-bold text-base sm:text-lg flex items-center gap-2 relative z-10">
                    <i class="fas fa-beer"></i> <span class="hidden sm:inline">Alcoholic</span>
                </button>
            </div>

            <!-- Shopping Cart Icon -->
            <div class="fixed top-24 sm:top-32 right-4 sm:right-8 z-50">
                <div class="relative">
                    <button onclick="toggleCart()"
                            class="bg-gradient-to-br from-orange-500 to-orange-600 w-14 h-14 sm:w-16 sm:h-16 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-all duration-300 text-white hover:shadow-orange-500/50">
                        <i class="fas fa-shopping-cart text-xl sm:text-2xl"></i>
                    </button>
                    <div class="absolute -top-2 -right-2 bg-red-500 border-2 border-white rounded-full w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center cart-badge">
                        <span id="cart-count" class="text-white font-bold text-xs sm:text-sm">0</span>
                    </div>
                </div>
            </div>

            <!-- Menu Items Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8" id="menu-items-container">
                @forelse($facility->menuItems as $item)
                    <div class="menu-item bg-white rounded-xl shadow-lg overflow-hidden border-2 border-transparent hover:border-orange-300" data-category="{{ $item->category }}">
                        @php
                            $imagePath = null;
                            if($item->image) {
                                if(file_exists(public_path($item->image))) {
                                    $imagePath = asset($item->image);
                                } elseif(file_exists(public_path('storage/' . $item->image))) {
                                    $imagePath = asset('storage/' . $item->image);
                                }
                            }
                        @endphp

                        @if($imagePath)
                            <img src="{{ $imagePath }}"
                                 alt="{{ $item->name }}"
                                 class="w-full h-48 object-cover"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-48 bg-gradient-to-br from-orange-200 to-orange-300 flex items-center justify-center\'><i class=\'fas fa-coffee text-white text-6xl opacity-50\'></i></div>';">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-orange-200 to-orange-300 flex items-center justify-center">
                                <i class="fas fa-coffee text-white text-6xl opacity-50"></i>
                            </div>
                        @endif

                        <div class="p-6">
                            <h4 class="text-xl sm:text-2xl font-bold mb-3 text-gray-900">{{ $item->name }}</h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $item->description ?? 'Delicious item from our menu' }}</p>

                            <div class="flex items-center gap-3 sm:gap-4 mb-4 bg-gray-50 rounded-lg p-3">
                                <span class="text-sm sm:text-base font-bold text-gray-700">Qty:</span>
                                <button onclick="decreaseQty({{ $item->id }})"
                                        class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-all duration-300 font-bold text-lg sm:text-xl transform active:scale-90">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span id="qty-{{ $item->id }}" class="text-xl sm:text-2xl font-bold text-orange-600 min-w-[30px] sm:min-w-[40px] text-center">1</span>
                                <button onclick="increaseQty({{ $item->id }})"
                                        class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-all duration-300 font-bold text-lg sm:text-xl transform active:scale-90">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="primary-orange text-white rounded-xl py-3 text-center flex items-center justify-center gap-2 shadow-md">
                                    <i class="fas fa-tag"></i>
                                    <span class="text-base sm:text-lg font-bold">{{ number_format($item->price) }} RWF</span>
                                </div>
                                <button onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})"
                                        class="primary-orange text-white rounded-xl py-3 text-base sm:text-lg font-bold hover:bg-orange-600 transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center justify-center gap-2 shadow-md">
                                    <i class="fas fa-cart-plus"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-12">
                        <i class="fas fa-coffee text-6xl mb-4 opacity-30"></i>
                        <p class="text-xl font-semibold">No menu items available</p>
                        <p class="text-sm">Check back soon for our delicious offerings!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Gallery Section -->
        <div class="mb-16 fade-in-up">
            <h3 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i class="fas fa-images text-orange"></i> Our Gallery
            </h3>
            <hr class="border-gray-300 border-2 mb-12">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($facility->gallery as $image)
                    @php
                        $galleryPath = null;
                        if($image->image) {
                            if(file_exists(public_path('storage/' . $image->image))) {
                                $galleryPath = asset('storage/' . $image->image);
                            } elseif(file_exists(public_path($image->image))) {
                                $galleryPath = asset($image->image);
                            }
                        }
                    @endphp

                    <div class="gallery-item relative group overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer">
                        @if($galleryPath)
                            <img src="{{ $galleryPath }}"
                                 alt="Gallery"
                                 class="w-full h-64 object-cover"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-64 bg-gradient-to-br from-orange-200 to-orange-400 flex items-center justify-center\'><i class=\'fas fa-image text-white text-6xl opacity-50\'></i></div>';">
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-orange-200 to-orange-400 flex items-center justify-center">
                                <i class="fas fa-image text-white text-6xl opacity-50"></i>
                            </div>
                        @endif
                        <div class="gallery-overlay absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-400 flex items-center justify-center">
                            <i class="fas fa-search-plus text-white text-5xl transform group-hover:scale-110 transition-transform duration-300"></i>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-8">
                        <i class="fas fa-images text-6xl mb-4 opacity-30"></i>
                        <p class="text-xl">No gallery images available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-10 text-center">
        <p class="text-lg flex items-center justify-center gap-2 mb-2">
            <i class="far fa-copyright"></i> 2024 All Rights Reserved
        </p>
        <p class="text-sm text-gray-400">Made with <i class="fas fa-heart text-red-500 animate-pulse"></i> for Coffee Lovers</p>
    </footer>

    <!-- Cart Modal -->
    <div id="cart-modal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 modal-backdrop">
        <div class="modal-content bg-white rounded-2xl p-6 sm:p-8 max-w-2xl w-full max-h-[85vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center mb-6 sticky top-0 bg-white pb-4 border-b-2 border-orange-200 z-10">
                <h3 class="text-2xl sm:text-3xl font-bold flex items-center gap-3">
                    <i class="fas fa-shopping-cart text-orange"></i> Your Order
                </h3>
                <button onclick="toggleCart()" class="text-3xl sm:text-4xl text-gray-400 hover:text-orange transition-all duration-300 transform hover:rotate-90">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="cart-items" class="space-y-3 mb-6 min-h-[200px]"></div>

            <div class="border-t-2 border-orange-200 pt-4 sticky bottom-0 bg-white">
                <div class="flex justify-between items-center text-xl sm:text-2xl font-bold mb-6">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-receipt text-orange"></i> Total:
                    </span>
                    <span id="cart-total" class="text-orange">0 RWF</span>
                </div>
                <button onclick="proceedToCheckout()" class="btn-hover primary-orange text-white w-full py-3 sm:py-4 rounded-xl text-lg sm:text-xl font-bold hover:bg-orange-600 transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-3 shadow-lg">
                    <i class="fas fa-credit-card"></i> Proceed to Checkout
                </button>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkout-modal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 modal-backdrop">
        <div class="modal-content bg-white rounded-2xl p-6 sm:p-8 max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center mb-8 pb-4 border-b-2 border-orange-200">
                <h3 class="text-2xl sm:text-3xl font-bold flex items-center gap-3">
                    <i class="fas fa-cash-register text-orange"></i> Checkout
                </h3>
                <button onclick="toggleCheckout()" class="text-3xl sm:text-4xl text-gray-400 hover:text-orange transition-all duration-300 transform hover:rotate-90">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="match-orange rounded-xl p-6 mb-6 border-2 border-orange-200">
                <h4 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-list text-orange"></i> Order Summary
                </h4>
                <div id="checkout-items" class="space-y-2 mb-4"></div>
                <div class="border-t-2 border-orange-300 pt-3 mt-3">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total Amount:</span>
                        <span id="checkout-total" class="text-orange">0 RWF</span>
                    </div>
                </div>
            </div>

            <form id="checkout-form" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-user text-orange"></i> Full Name *
                        </label>
                        <input type="text" name="customer_name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange focus:outline-none transition-all duration-300 focus:ring-2 focus:ring-orange/20" placeholder="Enter your name">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-phone text-orange"></i> Phone Number *
                        </label>
                        <input type="tel" name="customer_phone" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange focus:outline-none transition-all duration-300 focus:ring-2 focus:ring-orange/20" placeholder="+250 XXX XXX XXX">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-envelope text-orange"></i> Email (Optional)
                    </label>
                    <input type="email" name="customer_email" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange focus:outline-none transition-all duration-300 focus:ring-2 focus:ring-orange/20" placeholder="your.email@example.com">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-orange"></i> Delivery Address (Optional)
                    </label>
                    <textarea name="delivery_address" rows="2" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange focus:outline-none transition-all duration-300 focus:ring-2 focus:ring-orange/20" placeholder="Enter delivery address if needed"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-credit-card text-orange"></i> Payment Method *
                    </label>
                    <select name="payment_method" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange focus:outline-none transition-all duration-300 focus:ring-2 focus:ring-orange/20">
                        <option value="">Select payment method</option>
                        <option value="cash">Cash on Delivery</option>
                        <option value="momo">Mobile Money (MTN/Airtel)</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-comment text-orange"></i> Special Instructions (Optional)
                    </label>
                    <textarea name="special_instructions" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange focus:outline-none transition-all duration-300 focus:ring-2 focus:ring-orange/20" placeholder="Any special requests or notes"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="toggleCheckout()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 sm:py-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 transform active:scale-95">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button type="submit" class="btn-hover flex-1 primary-orange text-white font-bold py-3 sm:py-4 rounded-xl hover:bg-orange-600 transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center justify-center gap-2 shadow-lg">
                        <i class="fas fa-check-circle"></i> Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 modal-backdrop">
        <div class="modal-content bg-white rounded-2xl p-8 max-w-md w-full text-center shadow-2xl">
            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 bouncing">
                <i class="fas fa-check text-white text-4xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-3">Order Placed!</h3>
            <p class="text-gray-600 mb-2">Your order number:</p>
            <p id="order-number" class="text-2xl font-bold text-orange mb-6">#ORD-12345</p>
            <p class="text-sm text-gray-600 mb-8">We'll contact you shortly!</p>
            <button onclick="closeSuccessModal()" class="btn-hover primary-orange text-white font-bold px-8 py-3 rounded-xl hover:bg-orange-600 transition-all duration-300 w-full transform hover:scale-105 active:scale-95">
                <i class="fas fa-home"></i> Continue
            </button>
        </div>
    </div>

<script>
    window.facilityId = {{ $facility->id }};
    window.facilityType = "{{ $facility->type }}";
</script>

<script>
let cart = {};
let isSubmitting = false;

window.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
});

function filterMenu(category) {
    const items = document.querySelectorAll('.menu-item');
    const buttons = document.querySelectorAll('.category-btn');

    buttons.forEach(btn => btn.classList.remove('active', 'text-orange', 'bg-white'));
    event.target.closest('.category-btn').classList.add('active', 'text-orange', 'bg-white');

    items.forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
            item.style.display = 'block';
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 10);
        } else {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            setTimeout(() => item.style.display = 'none', 300);
        }
    });
}

function increaseQty(itemId) {
    const qtyEl = document.getElementById(`qty-${itemId}`);
    let qty = parseInt(qtyEl.textContent);
    if (qty < 99) qtyEl.textContent = ++qty;
}

function decreaseQty(itemId) {
    const qtyEl = document.getElementById(`qty-${itemId}`);
    let qty = parseInt(qtyEl.textContent);
    if (qty > 1) qtyEl.textContent = --qty;
}

function addToCart(itemId, itemName, itemPrice) {
    const qty = parseInt(document.getElementById(`qty-${itemId}`).textContent);

    if (cart[itemId]) {
        cart[itemId].quantity += qty;
    } else {
        cart[itemId] = { name: itemName, price: itemPrice, quantity: qty };
    }

    updateCartDisplay();
    document.getElementById(`qty-${itemId}`).textContent = '1';

    // Animate cart badge
    const badge = document.querySelector('.cart-badge');
    badge.classList.add('cart-badge');
    setTimeout(() => badge.classList.remove('cart-badge'), 600);

    showNotification('✓ Item added to cart!', 'success');
}

function removeFromCart(itemId) {
    delete cart[itemId];
    updateCartDisplay();
    showNotification('Item removed', 'info');
}

function updateCartQuantity(itemId, change) {
    if (cart[itemId]) {
        cart[itemId].quantity += change;
        if (cart[itemId].quantity <= 0) delete cart[itemId];
        updateCartDisplay();
    }
}

function updateCartDisplay() {
    const cartCount = document.getElementById('cart-count');
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');

    let totalItems = 0, totalPrice = 0, cartHTML = '';

    for (const [id, item] of Object.entries(cart)) {
        totalItems += item.quantity;
        totalPrice += item.price * item.quantity;

        cartHTML += `
            <div class="flex justify-between items-center p-4 bg-orange-50 rounded-lg border-l-4 border-orange hover:shadow-md transition-all duration-300">
                <div class="flex-1">
                    <h4 class="font-bold text-base sm:text-lg text-gray-900">${item.name}</h4>
                    <div class="flex items-center gap-3 mt-2">
                        <button onclick="updateCartQuantity(${id}, -1)" class="w-7 h-7 bg-orange text-white rounded hover:bg-orange-600 transition-all duration-300 flex items-center justify-center transform active:scale-90">
                            <i class="fas fa-minus text-xs"></i>
                        </button>
                        <span class="font-bold text-orange-600 text-lg">${item.quantity}</span>
                        <button onclick="updateCartQuantity(${id}, 1)" class="w-7 h-7 bg-orange text-white rounded hover:bg-orange-600 transition-all duration-300 flex items-center justify-center transform active:scale-90">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                        <span class="text-gray-600 text-sm ml-2">× ${item.price.toLocaleString()} RWF</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 ml-4">
                    <span class="font-bold text-orange-600 text-lg whitespace-nowrap">${(item.price * item.quantity).toLocaleString()} RWF</span>
                    <button onclick="removeFromCart(${id})" class="text-red-600 hover:text-red-800 transition-all duration-300 transform hover:scale-110">
                        <i class="fas fa-trash text-lg"></i>
                    </button>
                </div>
            </div>
        `;
    }

    if (cartCount) cartCount.textContent = totalItems;
    if (cartItems) cartItems.innerHTML = cartHTML || '<div class="text-center text-gray-500 py-12"><i class="fas fa-shopping-cart text-5xl mb-3 opacity-30 block"></i><p class="text-lg">Your cart is empty</p></div>';
    if (cartTotal) cartTotal.textContent = totalPrice.toLocaleString() + ' RWF';
}

function toggleCart() {
    const modal = document.getElementById('cart-modal');
    modal.classList.toggle('hidden');
    document.body.style.overflow = modal.classList.contains('hidden') ? 'auto' : 'hidden';
}

function toggleCheckout() {
    const checkoutModal = document.getElementById('checkout-modal');
    const cartModal = document.getElementById('cart-modal');

    checkoutModal.classList.toggle('hidden');

    if (!checkoutModal.classList.contains('hidden')) {
        if (cartModal) cartModal.classList.add('hidden');
        populateCheckoutSummary();
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
}

function proceedToCheckout() {
    if (Object.keys(cart).length === 0) {
        showNotification('⚠ Your cart is empty!', 'warning');
        return;
    }
    toggleCheckout();
}

function populateCheckoutSummary() {
    const checkoutItems = document.getElementById('checkout-items');
    const checkoutTotal = document.getElementById('checkout-total');

    let totalPrice = 0, itemsHTML = '';

    for (const [id, item] of Object.entries(cart)) {
        totalPrice += item.price * item.quantity;
        itemsHTML += `
            <div class="flex justify-between text-sm sm:text-base py-2 border-b border-orange-200">
                <span class="text-gray-700">${item.name} <span class="text-orange-600 font-bold">× ${item.quantity}</span></span>
                <span class="font-semibold">${(item.price * item.quantity).toLocaleString()} RWF</span>
            </div>
        `;
    }

    if (checkoutItems) checkoutItems.innerHTML = itemsHTML;
    if (checkoutTotal) checkoutTotal.textContent = totalPrice.toLocaleString() + ' RWF';
}

function showNotification(message, type = 'success') {
    const colors = { success: 'bg-green-500', error: 'bg-red-500', warning: 'bg-yellow-500', info: 'bg-blue-500' };
    const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };

    const notification = document.createElement('div');
    notification.className = `notification fixed top-24 right-4 sm:right-8 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-2xl z-[9999] flex items-center gap-2 max-w-sm`;
    notification.innerHTML = `<i class="fas ${icons[type]}"></i><span>${message}</span>`;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function closeSuccessModal() {
    document.getElementById('success-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function closeAllModals() {
    ['cart-modal', 'checkout-modal', 'success-modal'].forEach(id => {
        const modal = document.getElementById(id);
        if (modal) modal.classList.add('hidden');
    });
    document.body.style.overflow = 'auto';
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (isSubmitting) {
            showNotification('⏳ Please wait...', 'info');
            return;
        }

        if (Object.keys(cart).length === 0) {
            showNotification('⚠️ Cart is empty!', 'warning');
            return;
        }

        isSubmitting = true;
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<div class="spinner inline-block mr-2"></div> Processing...';

        const formData = new FormData(this);
        const total = Object.values(cart).reduce((sum, item) => sum + (item.price * item.quantity), 0);

        const facilityType = window.facilityType || 'restaurant';
        const bookingType = facilityType === 'coffee_shop' ? 'coffee' : facilityType;

        const orderData = {
            booking_type: bookingType,
            facility_id: window.facilityId || 1,
            customer_name: formData.get('customer_name'),
            customer_phone: formData.get('customer_phone'),
            customer_email: formData.get('customer_email'),
            delivery_address: formData.get('delivery_address'),
            payment_method: formData.get('payment_method'),
            special_instructions: formData.get('special_instructions'),
            items: cart,
            total: total,
            order_date: new Date().toISOString()
        };

        console.log('📤 Sending Order Data:', orderData);

        try {
            const response = await fetch('/orders/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(orderData)
            });

            const data = await response.json();
            console.log('📥 Server Response:', data);

            if (response.ok && data.success) {
                const orderNumber = data.order_number || data.booking_reference || data.order_id || ('ORD-' + Date.now());
                document.getElementById('order-number').textContent = '#' + orderNumber;

                document.getElementById('checkout-modal').classList.add('hidden');
                document.getElementById('success-modal').classList.remove('hidden');

                this.reset();
                cart = {};
                updateCartDisplay();

                showNotification('✅ Order placed successfully!', 'success');
            } else {
                throw new Error(data.message || 'Failed to place order');
            }
        } catch (error) {
            console.error('❌ Order Error:', error);
            showNotification('❌ Failed: ' + error.message, 'error');
        } finally {
            isSubmitting = false;
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
});

document.addEventListener('click', function(e) {
    if (e.target.id === 'cart-modal') toggleCart();
    if (e.target.id === 'checkout-modal') toggleCheckout();
    if (e.target.id === 'success-modal') closeSuccessModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAllModals();
});
</script>
@endsection
