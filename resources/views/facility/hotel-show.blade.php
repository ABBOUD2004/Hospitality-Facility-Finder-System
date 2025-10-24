@extends('layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Navbar Styles */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: linear-gradient(135deg, #fff 0%, #fef5ed 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-logo {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #F97316, #FB923C);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-menu {
            display: flex;
            gap: 2.5rem;
            list-style: none;
            align-items: center;
        }

        .navbar-menu a {
            text-decoration: none;
            color: #1f2937;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 0;
        }

        .navbar-menu a:hover {
            color: #F97316;
        }

        .navbar-menu a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #F97316, #FB923C);
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .navbar-menu a:hover::after {
            width: 100%;
        }

        .navbar-menu a.active {
            color: #F97316;
        }

        .navbar-menu a.active::after {
            width: 100%;
        }

        .navbar-auth {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-login {
            padding: 0.6rem 1.5rem;
            border: 2px solid #F97316;
            color: #F97316;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-login:hover {
            background: #F97316;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        .btn-register {
            padding: 0.6rem 1.5rem;
            background: linear-gradient(135deg, #F97316, #FB923C);
            color: white;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            font-size: 0.9rem;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(249, 115, 22, 0.4);
        }

        .navbar-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            background: none;
            border: none;
        }

        .navbar-toggle span {
            width: 28px;
            height: 3px;
            background: #F97316;
            border-radius: 2px;
            transition: 0.3s;
        }

        @media (max-width: 768px) {
            .navbar-menu {
                position: fixed;
                top: 70px;
                right: -100%;
                width: 280px;
                height: calc(100vh - 70px);
                background: white;
                flex-direction: column;
                padding: 2rem;
                box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
                transition: right 0.3s ease;
                align-items: flex-start;
                gap: 1.5rem;
            }

            .navbar-menu.active {
                right: 0;
            }

            .navbar-toggle {
                display: flex;
            }

            .navbar-auth {
                flex-direction: column;
                width: 100%;
                gap: 0.8rem;
            }

            .btn-login,
            .btn-register {
                width: 100%;
                text-align: center;
            }
        }

        /* Modal Animations */
        .modal-backdrop {
            backdrop-filter: blur(8px);
            animation: fadeIn 0.25s ease;
        }

        .modal-content {
            animation: slideUp 0.32s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Steps */
        .step-indicator {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .step-indicator.active {
            background: linear-gradient(135deg, #F97316, #FB923C);
            color: #fff;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.35);
        }

        .step-indicator.completed {
            background: #10B981;
            color: #fff;
        }

        .step-indicator.pending {
            background: #E5E7EB;
            color: #9CA3AF;
        }

        /* Scrollable modal */
        .booking-modal {
            max-height: 90vh;
            overflow-y: auto;
        }

        .booking-modal::-webkit-scrollbar {
            width: 8px;
        }

        .booking-modal::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .booking-modal::-webkit-scrollbar-thumb {
            background: #F97316;
            border-radius: 10px;
        }

        .step-content {
            min-height: 400px;
        }

        .payment-option {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .payment-option.selected {
            border-color: #F97316 !important;
            background: #FFF7ED;
        }

        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #F97316;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .category-btn {
            transition: all 0.3s ease;
            cursor: pointer;
            background: white;
            border: 2px solid #e5e7eb;
        }

        .category-btn:hover {
            transform: translateY(-2px);
            border-color: #F97316;
        }

        .category-btn.active {
            background: linear-gradient(135deg, #F97316, #FB923C);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.35);
            border-color: transparent;
        }

        .alert-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideInRight 0.25s ease;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Service Cards Animation */
        .service-card {
            transition: all 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
        }

        /* Room Card Animation */
        .room-card {
            transition: all 0.3s ease;
        }

        .room-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(249, 115, 22, 0.15);
        }
    </style>

    <div class="bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen">

        <div class="w-full bg-white min-h-screen">
            <!-- Hero Image -->
            <section class="relative w-full px-4 sm:px-6 lg:px-8 pt-6">
                <div
                    class="relative h-[280px] sm:h-[350px] md:h-[420px] lg:h-[480px] overflow-hidden rounded-3xl border-8 border-orange-100 shadow-2xl">
                    <img src="{{ asset($facility->image ?? 'images/placeholder.jpg') }}" alt="{{ $facility->name }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div
                        class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 text-white text-sm font-semibold bg-black/60 backdrop-blur-sm rounded-2xl px-6 py-3 shadow-lg">
                        <p class="text-xs sm:text-sm opacity-90 flex items-center gap-2">
                            <i class="fas fa-tag"></i> Average price
                        </p>
                        <p class="text-base sm:text-xl font-bold">
                            {{ number_format($facility->rooms->avg('price_rwf') ?? 175000) }} RWF /
                            {{ $facility->rooms->avg('price_usd') ?? 70 }}$
                        </p>
                    </div>
                </div>
            </section>

            <!-- Hotel Description Card -->
            <section class="px-4 sm:px-6 lg:px-8 -mt-16 sm:-mt-20 relative z-10 pb-6 sm:pb-8">
                <div
                    class="max-w-4xl mx-auto bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl shadow-2xl border-2 border-orange-200 p-5 sm:p-8 relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 uppercase tracking-wide">
                                {{ $facility->name }}
                            </h1>
                            <i class="fas fa-certificate text-orange-500 text-xl sm:text-2xl"></i>
                        </div>
                        <div class="flex items-center gap-0.5" aria-label="Hotel rating: 4 stars">
                            @for($i = 0; $i < 4; $i++)
                                <i class="fas fa-star text-orange-500 text-sm sm:text-base"></i>
                            @endfor
                            <span class="text-gray-400 text-xs sm:text-sm ml-1">4 Stars</span>
                        </div>
                    </div>

                    <div class="w-full h-0.5 bg-gradient-to-r from-orange-500 to-orange-300 rounded-full mb-4"></div>

                    <h2 class="text-base sm:text-lg md:text-xl font-bold text-orange-600 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Hotel Description
                    </h2>

                    <div class="bg-white/70 backdrop-blur-sm rounded-xl p-4 sm:p-6 shadow-sm mb-4">
                        <p class="text-gray-800 leading-relaxed text-xs sm:text-sm md:text-base">
                            <span
                                class="font-semibold text-gray-900">{{ strtoupper(explode(' ', $facility->name)[0] ?? 'HOTEL') }}</span>
                            {{ $facility->description ?? 'is a four stars hotel located in the center of the city with amazing facilities and world-class service.' }}
                        </p>
                    </div>

                    <div class="text-right">
                        <a href="{{ url('/hotels') }}"
                            class="inline-flex items-center text-orange-600 hover:text-orange-700 font-semibold text-xs sm:text-sm group transition-all">
                            <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                            Back to Hotels
                        </a>
                    </div>
                </div>
            </section>

            <!-- Services Section -->
            <section class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                <div class="max-w-5xl mx-auto">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold mb-4 text-gray-800 flex items-center gap-3">
                        <i class="fas fa-concierge-bell text-orange-500"></i>
                        Services we offer
                    </h2>
                    <div class="h-0.5 w-full bg-gradient-to-r from-orange-500 to-transparent mb-5"></div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
                        @php
                            $services = [
                                'Free car parking' => '<i class="fas fa-parking text-orange-500 text-4xl sm:text-5xl"></i>',
                                'Swimming Pool' => '<i class="fas fa-swimming-pool text-orange-500 text-4xl sm:text-5xl"></i>',
                                'Fitness center' => '<i class="fas fa-dumbbell text-orange-500 text-4xl sm:text-5xl"></i>',
                                'Free wifi' => '<i class="fas fa-wifi text-orange-500 text-4xl sm:text-5xl"></i>',
                                'Meetings' => '<i class="fas fa-users text-orange-500 text-4xl sm:text-5xl"></i>',
                                'Yoga class for adults' => '<i class="fas fa-spa text-orange-500 text-4xl sm:text-5xl"></i>',
                            ];
                        @endphp

                        @foreach($facility->services as $service)
                            <div
                                class="service-card bg-white border-2 border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-orange-300 transition-all duration-200 p-4 sm:p-5 flex flex-col items-center text-center">
                                <div class="mb-3">
                                    {!! $services[$service->name] ?? '<i class="fas fa-check-circle text-orange-500 text-4xl sm:text-5xl"></i>' !!}
                                </div>
                                <p class="text-xs sm:text-sm font-semibold text-gray-700">{{ $service->name }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Explore Rooms Button -->
            <div class="text-center py-6 sm:py-8">
                <a href="#rooms"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-10 sm:px-16 py-3 sm:py-4 rounded-full text-sm sm:text-base md:text-lg font-bold shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-bed"></i>
                    Explore our rooms
                </a>
            </div>

            <!-- Rooms Section with Categories -->
          <section id="rooms" class="px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="max-w-6xl mx-auto">
                <h3 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <i class="fas fa-door-open text-orange-500"></i>
                    Our Rooms
                </h3>
                    <hr class="border-gray-300 border-2 mb-8">


                    <!-- Enhanced Category Navbar with Animations -->
                    <div class="relative mb-10">



                        <!-- Main Container -->
                        <div class="relative    ">
                            <!-- Title -->

  <!-- Category Filter with Flexbox -->
                <div class="flex flex-wrap gap-3 justify-center mb-10 p-6 bg-gradient-to-r from-orange-50 to-orange-100 rounded-2xl shadow-lg border border-orange-200">
                    <button onclick="filterRooms('all')" class="category-btn flex items-center gap-2 px-6 py-3 bg-white text-orange-600 font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 active:scale-95">
                        <i class="fas fa-th-large"></i>All Rooms
                    </button>
                    <button onclick="filterRooms('VIP')" class="category-btn flex items-center gap-2 px-6 py-3 bg-transparent text-orange-600 font-bold rounded-xl hover:bg-white hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <i class="fas fa-crown"></i>VIP
                    </button>
                    <button onclick="filterRooms('Presidential')" class="category-btn flex items-center gap-2 px-6 py-3 bg-transparent text-orange-600 font-bold rounded-xl hover:bg-white hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <i class="fas fa-gem"></i>Presidential
                    </button>
                    <button onclick="filterRooms('Suite')" class="category-btn flex items-center gap-2 px-6 py-3 bg-transparent text-orange-600 font-bold rounded-xl hover:bg-white hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <i class="fas fa-star"></i>Suite
                    </button>
                    <button onclick="filterRooms('Deluxe')" class="category-btn flex items-center gap-2 px-6 py-3 bg-transparent text-orange-600 font-bold rounded-xl hover:bg-white hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <i class="fas fa-medal"></i>Deluxe
                    </button>
                    <button onclick="filterRooms('Standard')" class="category-btn flex items-center gap-2 px-6 py-3 bg-transparent text-orange-600 font-bold rounded-xl hover:bg-white hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <i class="fas fa-bed"></i>Standard
                    </button>
                    <button onclick="filterRooms('Economy')" class="category-btn flex items-center gap-2 px-6 py-3 bg-transparent text-orange-600 font-bold rounded-xl hover:bg-white hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <i class="fas fa-coins"></i>Economy
                    </button>
                </div>


                            <style>
                                .match-orange button.category-btn {
                                    background: transparent;
                                    border: none;
                                }

                                .match-orange button.category-btn.active {
                                    background: white;
                                    color: #F97316;
                                    /* لون برتقالي */
                                }

                                .match-orange button.category-btn:hover {
                                    background: white;
                                    color: #EA580C;
                                    box-shadow: 0 6px 14px rgba(234, 88, 12, 0.4);
                                }

                                .match-orange i {
                                    min-width: 20px;
                                }
                            </style>


                            <!-- Active Category Indicator -->
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-filter mr-2"></i>
                                    Showing: <span id="active-category" class="font-bold text-orange-600">All Rooms</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Rooms List -->
                    <div class="space-y-5 sm:space-y-6">
                        @foreach($facility->rooms as $room)
                            <div class="room-card bg-gradient-to-br from-orange-50 to-amber-50 border-3 border-orange-300 rounded-3xl shadow-lg overflow-hidden relative"
                                data-category="{{ $room->category }}">
                                <div
                                    class="flex items-center justify-between bg-gradient-to-r from-amber-100 to-orange-100 px-4 sm:px-6 py-3 sm:py-4 border-b-2 border-orange-200">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-800">
                                            {{ $room->name }}
                                        </h3>
                                        @if($room->category)
                                            <span
                                                class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1">
                                                <i class="fas fa-tag"></i> {{ $room->category }}
                                            </span>
                                        @endif
                                    </div>
                                    @auth
                                        <button type="button"
                                            onclick="openBookingModal({{ $room->id }}, '{{ $room->name }}', {{ (int) ($room->price_rwf ?? 0) }}, {{ (int) ($room->price_usd ?? 0) }}, {{ (int) ($room->availability ?? 0) }})"
                                            class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 sm:px-8 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-bold hover:from-orange-600 hover:to-orange-700 shadow-md transition-all duration-200 transform hover:scale-105 flex items-center gap-2">
                                            <i class="fas fa-calendar-check"></i> Book now
                                        </button>
                                    @else
                                        <button type="button" onclick="openLoginModal('{{ $room->name }}')"
                                            class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 sm:px-8 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-bold hover:from-orange-600 hover:to-orange-700 shadow-md transition-all duration-200 transform hover:scale-105 flex items-center gap-2">
                                            <i class="fas fa-calendar-check"></i> Book now
                                        </button>
                                    @endauth
                                </div>

                                <div class="p-4 sm:p-6">
                                    <div class="flex flex-col md:flex-row gap-3 sm:gap-5 mb-4">
                                        <div class="w-full md:w-48 lg:w-56 h-36 sm:h-40 md:h-32 lg:h-36 flex-shrink-0">
                                            <img src="{{ asset($room->image ?? 'images/placeholder.jpg') }}"
                                                alt="{{ $room->name }}"
                                                class="w-full h-full object-cover rounded-xl shadow-md border-2 border-white">
                                        </div>
                                        <div class="flex-1">
                                            <h4
                                                class="text-orange-600 font-bold text-sm sm:text-base mb-2 flex items-center gap-2">
                                                <i class="fas fa-info-circle"></i> Room Description
                                            </h4>
                                            <div class="bg-white/70 backdrop-blur-sm rounded-lg p-2.5 sm:p-4 shadow-sm">
                                                <p class="text-gray-800 leading-relaxed text-xs sm:text-sm">
                                                    {{ $room->description ?? 'Comfortable room with modern amenities, elegant design, and premium facilities for your perfect stay.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-1.5 sm:space-y-2 bg-white/60 rounded-xl p-3 sm:p-4 shadow-sm">
                                        <div class="flex items-baseline justify-between">
                                            <span
                                                class="text-gray-700 font-semibold text-xs sm:text-sm flex items-center gap-2">
                                                <i class="fas fa-money-bill-wave text-orange-500"></i> Room price
                                            </span>
                                            <span class="text-gray-900 font-bold text-sm sm:text-base">
                                                {{ number_format($room->price_rwf ?? 0) }} RWF
                                            </span>
                                        </div>
                                        <div class="flex items-baseline justify-end">
                                            <span class="text-gray-600 font-medium text-xs sm:text-sm">
                                                {{ $room->price_usd ?? 0 }} USD
                                            </span>
                                        </div>
                                        <div class="border-t border-gray-300 pt-1.5 sm:pt-2 mt-1.5 sm:mt-2">
                                            <div class="flex items-baseline justify-between">
                                                <span
                                                    class="text-gray-700 font-semibold text-xs sm:text-sm flex items-center gap-2">
                                                    <i class="fas fa-door-closed text-orange-500"></i> Room availability
                                                </span>
                                                <span class="text-gray-900 font-bold text-sm sm:text-base">
                                                    {{ $room->availability ?? 0 }} Rooms
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Gallery Section -->
            @if($facility->gallery->count() > 0)
                <section class="px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
                    <div class="max-w-5xl mx-auto">
                        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold mb-4 text-gray-800 flex items-center gap-3">
                            <i class="fas fa-images text-orange-500"></i> Our Gallery
                        </h2>
                        <div class="h-0.5 w-full bg-gradient-to-r from-orange-500 to-transparent mb-5"></div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4">
                            @foreach($facility->gallery as $img)
                                <div
                                    class="overflow-hidden rounded-xl shadow-md hover:shadow-lg transition-all duration-200 border-2 border-gray-200 hover:border-orange-300 cursor-pointer transform hover:scale-105">
                                    <img src="{{ asset($img->image) }}" alt="Gallery Image"
                                        class="w-full h-32 sm:h-40 object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            <!-- Footer -->
            <footer class="border-t-2 border-gray-300 py-6 bg-gradient-to-r from-gray-50 to-gray-100 mt-12">
                <p class="text-center text-gray-600 text-xs sm:text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-copyright"></i>
                    {{ date('Y') }} Design by <span class="font-bold text-orange-500">B.Moise</span>
                </p>
            </footer>
        </div>
    </div>

    <!-- Login Required Modal -->
    <div id="loginModal" class="modal-backdrop hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
        role="dialog" aria-modal="true" aria-labelledby="loginModalTitle">
        <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative">
            <button type="button" onclick="closeLoginModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition" aria-label="Close login modal">
                <i class="fas fa-times text-2xl"></i>
            </button>

            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center" aria-hidden="true">
                    <i class="fas fa-lock text-orange-500 text-4xl"></i>
                </div>
            </div>

            <h3 id="loginModalTitle" class="text-2xl font-bold text-center text-gray-800 mb-3">
                Login Required
            </h3>

            <p class="text-center text-gray-600 mb-8 leading-relaxed">
                To book a room in the hotel<br>
                you need to <span class="font-semibold text-orange-500">login</span> to your<br>
                account first.
            </p>

            <a href="{{ route('login') }}"
                class="block w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-semibold text-lg shadow-lg text-center hover:from-orange-600 hover:to-orange-700 transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-orange-500 font-semibold hover:underline">
                        <i class="fas fa-user-plus"></i> Sign up
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal-backdrop hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
        role="dialog" aria-modal="true" aria-labelledby="bookingModalTitle">
        <div class="booking-modal bg-white rounded-2xl shadow-2xl max-w-4xl w-full relative max-h-[90vh] overflow-y-auto">
            <button type="button" onclick="closeBookingModal()"
                class="sticky top-4 float-right z-10 text-gray-400 hover:text-gray-600 transition bg-white rounded-full p-2 shadow-lg mr-4"
                aria-label="Close booking modal">
                <i class="fas fa-times text-xl"></i>
            </button>

            <div class="p-8" id="bookingModalBody" tabindex="-1">
                <!-- Progress Steps -->
                <div class="flex items-center justify-between mb-8 overflow-x-auto pb-2" aria-label="Booking steps">
                    <div class="flex items-center gap-2 min-w-max">
                        <div class="step-indicator active" id="b-step-indicator-1">1</div>
                        <span class="text-xs font-medium text-gray-700 hidden sm:inline">Reservation</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2">
                        <div class="h-full bg-orange-500 transition-all" id="b-progress-1" style="width: 0%"></div>
                    </div>
                    <div class="flex items-center gap-2 min-w-max">
                        <div class="step-indicator pending" id="b-step-indicator-2">2</div>
                        <span class="text-xs font-medium text-gray-700 hidden sm:inline">Room Info</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2">
                        <div class="h-full bg-orange-500 transition-all" id="b-progress-2" style="width: 0%"></div>
                    </div>
                    <div class="flex items-center gap-2 min-w-max">
                        <div class="step-indicator pending" id="b-step-indicator-3">3</div>
                        <span class="text-xs font-medium text-gray-700 hidden sm:inline">Guest Info</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2">
                        <div class="h-full bg-orange-500 transition-all" id="b-progress-3" style="width: 0%"></div>
                    </div>
                    <div class="flex items-center gap-2 min-w-max">
                        <div class="step-indicator pending" id="b-step-indicator-4">4</div>
                        <span class="text-xs font-medium text-gray-700 hidden sm:inline">Payment</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2">
                        <div class="h-full bg-orange-500 transition-all" id="b-progress-4" style="width: 0%"></div>
                    </div>
                    <div class="flex items-center gap-2 min-w-max">
                        <div class="step-indicator pending" id="b-step-indicator-5">5</div>
                        <span class="text-xs font-medium text-gray-700 hidden sm:inline">Done</span>
                    </div>
                </div>

                <form id="booking-form" action="{{ route('bookings.store') }}" method="POST" data-prevent-double-submit>
                    @csrf
                    <input type="hidden" name="room_id" id="booking-room-id">
                    <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                    <input type="hidden" name="total_price_rwf" id="total-price-rwf">
                    <input type="hidden" name="total_price_usd" id="total-price-usd">
                    <input type="hidden" name="nights" id="nights-count">

                    <!-- Step 1: Reservation Details -->
                    <div id="b-step-1" class="step-content">
                        <div class="flex items-center gap-3 mb-6">
                            <i class="fas fa-calendar-alt text-4xl text-orange-500"></i>
                            <h2 id="bookingModalTitle" class="text-2xl font-bold text-gray-800">Reservation Details</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="b-checkin"
                                    class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-sign-in-alt text-orange-500"></i> Check-in Date & Time
                                </label>
                                <input type="datetime-local" name="checkin_date" id="b-checkin" required
                                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-orange-500 transition">
                            </div>
                            <div>
                                <label for="b-checkout"
                                    class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-sign-out-alt text-orange-500"></i> Checkout Date & Time
                                </label>
                                <input type="datetime-local" name="checkout_date" id="b-checkout" required
                                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-orange-500 transition">
                            </div>
                            <div>
                                <label for="b-adults"
                                    class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-user text-orange-500"></i> Number of Adults
                                </label>
                                <select name="adults" id="b-adults" required
                                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-orange-500 transition">
                                    <option value="">Select</option>
                                    <option value="1">1 Adult</option>
                                    <option value="2">2 Adults</option>
                                    <option value="3">3 Adults</option>
                                    <option value="4">4 Adults</option>
                                </select>
                            </div>
                            <div>
                                <label for="b-children"
                                    class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-child text-orange-500"></i> Number of Children
                                </label>
                                <select name="children" id="b-children"
                                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-orange-500 transition">
                                    <option value="0">0 Children</option>
                                    <option value="1">1 Child</option>
                                    <option value="2">2 Children</option>
                                    <option value="3">3 Children</option>
                                </select>
                            </div>
                        </div>

                        <button type="button" onclick="goToBookingStep(2)"
                            class="mt-8 w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition-all transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-right"></i> Proceed
                        </button>
                    </div>

                    <!-- Step 2: Room Information -->
                    <div id="b-step-2" class="step-content hidden">
                        <div class="flex items-center gap-3 mb-6">
                            <i class="fas fa-hotel text-4xl text-orange-500"></i>
                            <h2 class="text-2xl font-bold text-gray-800">Room Information</h2>
                        </div>

                        <div class="bg-orange-50 border-2 border-orange-200 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 flex items-center gap-2" id="b-room-name">
                                <i class="fas fa-door-open text-orange-500"></i> Premium Room
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Price per night</p>
                                    <p class="text-2xl font-bold text-orange-600" id="b-room-price-rwf">175,000 RWF</p>
                                    <p class="text-lg text-gray-700" id="b-room-price-usd">70 USD</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Available</p>
                                    <p class="text-xl font-semibold text-gray-800" id="b-room-availability">5 rooms</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3 bg-gray-50 rounded-xl p-6">
                            <div class="flex justify-between"><span class="text-gray-700 font-medium"><i
                                        class="fas fa-sign-in-alt text-orange-500 mr-2"></i>Check-in:</span><span
                                    class="text-gray-900 font-semibold" id="b-display-checkin">-</span></div>
                            <div class="flex justify-between"><span class="text-gray-700 font-medium"><i
                                        class="fas fa-sign-out-alt text-orange-500 mr-2"></i>Checkout:</span><span
                                    class="text-gray-900 font-semibold" id="b-display-checkout">-</span></div>
                            <div class="flex justify-between"><span class="text-gray-700 font-medium"><i
                                        class="fas fa-moon text-orange-500 mr-2"></i>Nights:</span><span
                                    class="text-gray-900 font-semibold" id="b-display-nights">-</span></div>
                            <div class="flex justify-between"><span class="text-gray-700 font-medium"><i
                                        class="fas fa-users text-orange-500 mr-2"></i>Guests:</span><span
                                    class="text-gray-900 font-semibold" id="b-display-guests">-</span></div>
                            <div class="flex justify-between pt-3 border-t-2 border-orange-300"><span
                                    class="text-gray-800 font-bold text-lg"><i
                                        class="fas fa-money-bill-wave text-orange-500 mr-2"></i>Total:</span><span
                                    class="text-orange-600 font-bold text-2xl" id="b-display-total"
                                    aria-live="polite">-</span></div>
                        </div>

                        <button type="button" onclick="goToBookingStep(3)"
                            class="mt-8 w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition-all transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-check"></i> Accept & Continue
                        </button>
                    </div>

                    <!-- Step 3: Guest Information -->
                    <div id="b-step-3" class="step-content hidden">
                        <div class="flex items-center gap-3 mb-6">
                            <i class="fas fa-user-circle text-4xl text-orange-500"></i>
                            <h2 class="text-2xl font-bold text-gray-800">Guest Information</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="b-firstname"
                                    class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-id-card text-orange-500"></i> First Name
                                </label>
                                <input type="text" name="guest_firstname" id="b-firstname" required
                                    placeholder="Enter first name"
                                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-orange-500 transition">
                            </div>
                            <div>
                                <label for="b-lastname"
                                    class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-id-card text-orange-500"></i> Last Name
                                </label>
                                <input type="text" name="guest_lastname" id="b-lastname" required
                                    placeholder="Enter last name"
                                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-orange-500 transition">
                            </div>
                            <div>
                                <label for="b-email"
                                    class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-envelope text-orange-500"></i> Email Address
                                </label>
                                <input type="email" name="guest_email" id="b-email" required placeholder="example@email.com"
                                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-orange-500 transition">
                            </div>
                            <div>
                                <label for="b-phone"
                                    class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-phone text-orange-500"></i> Phone Number
                                </label>
                                <input type="tel" name="guest_phone" id="b-phone" required placeholder="+250 xxx xxx xxx"
                                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-orange-500 transition"
                                    inputmode="tel" autocomplete="tel" pattern="^\+?[0-9\s\-]{7,15}$">
                            </div>
                            <div class="md:col-span-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="b-terms" required
                                        class="w-5 h-5 text-orange-500 rounded focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">I agree to the <a href="#"
                                            class="text-orange-500 font-semibold hover:underline">terms and
                                            conditions</a></span>
                                </label>
                            </div>
                        </div>

                        <button type="button" onclick="goToBookingStep(4)"
                            class="mt-8 w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition-all transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-credit-card"></i> Proceed to Payment
                        </button>
                    </div>

                    <!-- Step 4: Payment Methods -->
                    <div id="b-step-4" class="step-content hidden">
                        <div class="flex items-center gap-3 mb-6">
                            <i class="fas fa-credit-card text-4xl text-orange-500"></i>
                            <h2 class="text-2xl font-bold text-gray-800">Payment Methods</h2>
                        </div>

                        <p class="text-gray-600 mb-6 flex items-center gap-2">
                            <i class="fas fa-info-circle text-orange-500"></i>
                            Choose your preferred payment method
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="payment-option border-2 border-gray-200 rounded-xl p-6 cursor-pointer hover:border-orange-500 transition"
                                onclick="selectPayment('mtn', this)">
                                <div class="flex items-center justify-between mb-4">
                                    <input type="radio" name="payment_method" value="mtn" id="payment-mtn"
                                        class="w-5 h-5 text-orange-500" />
                                    <div class="text-3xl font-bold text-yellow-500 flex items-center gap-2">
                                        <i class="fas fa-mobile-alt"></i> MTN
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Enter your MTN Mobile number:</p>
                                <input type="tel" name="mtn_number" id="b-mtn-number" placeholder="078 xxx xxxx"
                                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500"
                                    inputmode="numeric" autocomplete="tel" pattern="^0\d{9}$" maxlength="10" disabled>
                            </div>

                            <div class="payment-option border-2 border-gray-200 rounded-xl p-6 cursor-pointer hover:border-orange-500 transition"
                                onclick="selectPayment('visa', this)">
                                <div class="flex items-center justify-between mb-4">
                                    <input type="radio" name="payment_method" value="visa" id="payment-visa"
                                        class="w-5 h-5 text-orange-500" />
                                    <div class="text-3xl font-bold text-blue-600 flex items-center gap-2">
                                        <i class="fab fa-cc-visa"></i> VISA
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600">Secure payment with Visa</p>
                            </div>
                        </div>

                        <div class="mt-6 bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-2 flex items-center gap-2">
                                <i class="fas fa-envelope text-orange-500"></i> Invoice will be sent to:
                                <span class="font-semibold" id="b-payment-email">-</span>
                            </p>
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <i class="fas fa-money-bill-wave text-orange-500"></i> Total Amount:
                                <span class="font-semibold" id="b-payment-total">-</span>
                            </p>
                        </div>

                        <!-- Transaction panel -->
                        <div id="b-transaction-panel" class="mt-6 bg-white border-2 border-gray-200 rounded-lg p-4 hidden">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span id="b-txn-status-badge"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        Processing
                                    </span>
                                    <span class="text-sm text-gray-600">Payment method: <span id="b-txn-provider"
                                            class="font-semibold">-</span></span>
                                </div>
                                <div class="flex items-center gap-2" id="b-txn-spinner">
                                    <span class="loading-spinner"></span>
                                    <span class="text-sm text-gray-600">Awaiting confirmation...</span>
                                </div>
                            </div>
                            <div class="mt-3 text-sm text-gray-600">Transaction Ref: <span id="b-txn-ref"
                                    class="font-semibold">-</span></div>
                        </div>

                        <button type="submit" id="confirm-booking-btn"
                            class="mt-8 w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition-all transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                            data-prevent-double-click>
                            <span id="btn-text" class="flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i> Confirm Booking
                            </span>
                            <span id="btn-loading" class="hidden items-center justify-center gap-2">
                                <span class="loading-spinner"></span> Processing...
                            </span>
                        </button>
                    </div>

                    <!-- Step 5: Confirmation -->
                    <div id="b-step-5" class="step-content hidden">
                        <div class="text-center">
                            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6"
                                aria-hidden="true">
                                <i class="fas fa-check-circle text-green-500 text-6xl"></i>
                            </div>

                            <h2 class="text-3xl font-bold text-gray-800 mb-3">Thank You!</h2>
                            <p class="text-lg text-gray-600 mb-8">Your booking has been confirmed successfully</p>

                            <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-2xl p-8 mb-8">
                                <p class="text-sm opacity-90 mb-2 flex items-center justify-center gap-2">
                                    <i class="fas fa-ticket-alt"></i> Booking Reference Number
                                </p>
                                <p class="text-4xl font-bold mb-4" id="b-booking-ref">BK-{{ date('Y') }}-0001</p>
                                <p class="text-sm opacity-90 flex items-center justify-center gap-2">
                                    <i class="fas fa-save"></i> Please save this reference number for your records
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-6 mb-6 text-left">
                                <h3 class="font-bold text-gray-800 mb-4 text-lg flex items-center gap-2">
                                    <i class="fas fa-file-invoice text-orange-500"></i> Booking Summary
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600"><i class="fas fa-user mr-2"></i>Guest Name:</span>
                                        <span class="font-semibold" id="b-confirm-name">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600"><i class="fas fa-door-open mr-2"></i>Room:</span>
                                        <span class="font-semibold" id="b-confirm-room">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600"><i class="fas fa-sign-in-alt mr-2"></i>Check-in:</span>
                                        <span class="font-semibold" id="b-confirm-checkin">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600"><i class="fas fa-sign-out-alt mr-2"></i>Checkout:</span>
                                        <span class="font-semibold" id="b-confirm-checkout">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600"><i class="fas fa-users mr-2"></i>Guests:</span>
                                        <span class="font-semibold" id="b-confirm-guests">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600"><i class="fas fa-credit-card mr-2"></i>Payment
                                            Method:</span>
                                        <span class="font-semibold" id="b-confirm-payment-method">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600"><i class="fas fa-check-circle mr-2"></i>Payment
                                            Status:</span>
                                        <span class="font-semibold" id="b-confirm-payment-status">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600"><i class="fas fa-receipt mr-2"></i>Transaction
                                            Ref:</span>
                                        <span class="font-semibold" id="b-confirm-txn-ref">-</span>
                                    </div>
                                    <div class="flex justify-between pt-3 border-t-2 border-gray-300">
                                        <span class="text-gray-800 font-bold"><i
                                                class="fas fa-money-bill-wave mr-2"></i>Total Paid:</span>
                                        <span class="font-bold text-orange-600" id="b-confirm-total">-</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 mb-6">
                                <p class="text-sm text-blue-800 flex items-center gap-2 justify-center">
                                    <i class="fas fa-envelope-open-text text-2xl"></i>
                                    <span><strong>Confirmation email sent!</strong><br>Check your email for booking details
                                        and payment receipt.</span>
                                </p>
                            </div>

                            <button type="button" onclick="closeBookingModal()"
                                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition-all transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                                <i class="fas fa-times-circle"></i> Done
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" aria-live="polite" aria-atomic="true"></div>

    <script>
        const BOOKINGS_STORE_URL = @json(route('bookings.store'));
        let selectedRoomName = '';
        let currentBookingStep = 1;
        let roomData = {};
        let bookingData = {};

        // Navbar Toggle
        document.getElementById('navbarToggle')?.addEventListener('click', function () {
            const menu = document.getElementById('navbarMenu');
            menu?.classList.toggle('active');
        });

        // Toast Notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `alert-toast bg-white rounded-lg shadow-2xl p-4 border-l-4 ${type === 'success' ? 'border-green-500' : 'border-red-500'}`;
            toast.setAttribute('role', 'status');
            toast.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                ${type === 'success'
                    ? '<i class="fas fa-check-circle text-green-500 text-2xl"></i>'
                    : '<i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>'
                }
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">${type === 'success' ? 'Success!' : 'Error!'}</p>
                                <p class="text-sm text-gray-600">${message}</p>
                            </div>
                        </div>
                    `;
            const container = document.getElementById('toast-container');
            if (container) {
                container.appendChild(toast);
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(400px)';
                    setTimeout(() => toast.remove(), 250);
                }, 4000);
            } else {
                alert(`${type.toUpperCase()}: ${message}`);
            }
        }

        // Room Category Filter
        function filterRooms(category) {
            const rooms = document.querySelectorAll('.room-card');
            const tabs = document.querySelectorAll('.category-btn');

            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            event.target.classList.add('active');

            rooms.forEach(room => {
                if (category === 'all' || room.dataset.category === category) {
                    room.style.display = 'block';
                } else {
                    room.style.display = 'none';
                }
            });
        }

        // Login Modal
        function openLoginModal(roomName = '') {
            selectedRoomName = roomName;
            const loginModal = document.getElementById('loginModal');
            if (loginModal) {
                loginModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeLoginModal() {
            const loginModal = document.getElementById('loginModal');
            if (loginModal) {
                loginModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Open booking modal
        function openBookingModal(roomId, roomName, priceRWF, priceUSD, availability) {
            roomData = {
                roomId,
                roomName,
                priceRWF: Number(priceRWF) || 0,
                priceUSD: Number(priceUSD) || 0,
                availability: Number(availability) || 0
            };

            document.getElementById('booking-room-id').value = roomId;
            document.getElementById('b-room-name').innerHTML = `<i class="fas fa-door-open text-orange-500"></i> ${roomName}`;
            document.getElementById('b-room-price-rwf').textContent = new Intl.NumberFormat().format(roomData.priceRWF) + ' RWF';
            document.getElementById('b-room-price-usd').textContent = roomData.priceUSD + ' USD';
            document.getElementById('b-room-availability').textContent = roomData.availability + ' rooms';

            const bookingModal = document.getElementById('bookingModal');
            bookingModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Reset form
            const form = document.getElementById('booking-form');
            form.reset();
            document.getElementById('booking-room-id').value = roomId;

            // Reset payment
            document.querySelectorAll('input[name="payment_method"]').forEach(r => { r.checked = false; });
            const mtnField = document.getElementById('b-mtn-number');
            if (mtnField) {
                mtnField.disabled = true;
                mtnField.required = false;
                mtnField.value = '';
            }

            // Reset steps
            currentBookingStep = 1;
            for (let i = 1; i <= 5; i++) {
                const stepEl = document.getElementById(`b-step-${i}`);
                const indicatorEl = document.getElementById(`b-step-indicator-${i}`);
                if (stepEl) stepEl.classList.add('hidden');
                if (indicatorEl) {
                    indicatorEl.classList.remove('active', 'completed');
                    indicatorEl.classList.add('pending');
                    indicatorEl.textContent = i;
                }
                if (i < 5) {
                    const progressEl = document.getElementById(`b-progress-${i}`);
                    if (progressEl) progressEl.style.width = '0%';
                }
            }

            document.getElementById('b-step-1').classList.remove('hidden');
            document.getElementById('b-step-indicator-1').classList.remove('pending');
            document.getElementById('b-step-indicator-1').classList.add('active');

            // Min check-in date
            const now = new Date();
            const tzoffset = now.getTimezoneOffset() * 60000;
            const localISOTime = new Date(Date.now() - tzoffset).toISOString().slice(0, 16);
            document.getElementById('b-checkin').min = localISOTime;

            setTimeout(() => {
                const body = document.getElementById('bookingModalBody');
                if (body) body.focus();
            }, 0);
        }

        function closeBookingModal() {
            const bookingModal = document.getElementById('bookingModal');
            bookingModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Navigate steps
        function goToBookingStep(targetStep) {
            if (!validateBookingStep(currentBookingStep)) return;

            document.getElementById(`b-step-${currentBookingStep}`).classList.add('hidden');
            document.getElementById(`b-step-indicator-${currentBookingStep}`).classList.remove('active');
            document.getElementById(`b-step-indicator-${currentBookingStep}`).classList.add('completed');
            document.getElementById(`b-step-indicator-${currentBookingStep}`).innerHTML = '<i class="fas fa-check"></i>';

            const progressEl = document.getElementById(`b-progress-${currentBookingStep}`);
            if (progressEl) progressEl.style.width = '100%';

            currentBookingStep = targetStep;
            document.getElementById(`b-step-${targetStep}`).classList.remove('hidden');
            document.getElementById(`b-step-indicator-${targetStep}`).classList.remove('pending');
            document.getElementById(`b-step-indicator-${targetStep}`).classList.add('active');

            populateBookingStepData(targetStep);
        }

        // Validate step
        function validateBookingStep(step) {
            if (step === 1) {
                const checkin = document.getElementById('b-checkin').value;
                const checkout = document.getElementById('b-checkout').value;
                const adults = document.getElementById('b-adults').value;

                if (!checkin || !checkout || !adults) {
                    showToast('Please fill in all required fields', 'error');
                    return false;
                }

                const checkinDate = new Date(checkin);
                const checkoutDate = new Date(checkout);

                if (checkoutDate <= checkinDate) {
                    showToast('Checkout date must be after check-in date', 'error');
                    return false;
                }

                document.getElementById('b-checkout').min = checkin;
                return true;
            }

            if (step === 3) {
                const firstname = document.getElementById('b-firstname').value.trim();
                const lastname = document.getElementById('b-lastname').value.trim();
                const email = document.getElementById('b-email').value.trim();
                const phone = document.getElementById('b-phone').value.trim();
                const terms = document.getElementById('b-terms').checked;

                if (!firstname || !lastname || !email || !phone || !terms) {
                    showToast('Please fill in all required fields and accept terms', 'error');
                    return false;
                }
                return true;
            }

            return true;
        }

        // Populate step data
        function populateBookingStepData(step) {
            if (step === 2) {
                const checkin = new Date(document.getElementById('b-checkin').value);
                const checkout = new Date(document.getElementById('b-checkout').value);
                let nights = Math.round((checkout - checkin) / (1000 * 60 * 60 * 24));
                if (nights < 1) nights = 1;

                const adults = parseInt(document.getElementById('b-adults').value || '0', 10);
                const children = parseInt(document.getElementById('b-children').value || '0', 10);

                bookingData.checkin = checkin;
                bookingData.checkout = checkout;
                bookingData.nights = nights;
                bookingData.adults = adults;
                bookingData.children = children;

                document.getElementById('b-display-checkin').textContent = checkin.toLocaleString('en-US', {
                    year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
                });
                document.getElementById('b-display-checkout').textContent = checkout.toLocaleString('en-US', {
                    year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
                });
                document.getElementById('b-display-nights').textContent = `${nights} night${nights > 1 ? 's' : ''}`;

                const guestsLabel = `${adults} Adult${adults > 1 ? 's' : ''}${children > 0 ? `, ${children} Child${children > 1 ? 'ren' : ''}` : ''}`;
                document.getElementById('b-display-guests').textContent = guestsLabel;

                const totalRWF = (roomData.priceRWF || 0) * nights;
                const totalUSD = (roomData.priceUSD || 0) * nights;
                bookingData.totalRWF = totalRWF;
                bookingData.totalUSD = totalUSD;

                document.getElementById('b-display-total').textContent = new Intl.NumberFormat().format(totalRWF) + ' RWF / ' + totalUSD + ' USD';
                document.getElementById('total-price-rwf').value = totalRWF;
                document.getElementById('total-price-usd').value = totalUSD;
                document.getElementById('nights-count').value = nights;
            }

            if (step === 4) {
                bookingData.email = document.getElementById('b-email').value.trim();
                bookingData.firstname = document.getElementById('b-firstname').value.trim();
                bookingData.lastname = document.getElementById('b-lastname').value.trim();
                bookingData.phone = document.getElementById('b-phone').value.trim();

                document.getElementById('b-payment-email').textContent = bookingData.email || '-';
                document.getElementById('b-payment-total').textContent = document.getElementById('b-display-total').textContent;
            }
        }

        // Select payment
        function selectPayment(method, el) {
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
            if (el) el.classList.add('selected');

            const radio = document.getElementById(`payment-${method}`);
            if (radio) radio.checked = true;

            const mtnInput = document.getElementById('b-mtn-number');
            if (mtnInput) {
                const isMtn = method === 'mtn';
                mtnInput.disabled = !isMtn;
                mtnInput.required = isMtn;
                if (!isMtn) mtnInput.value = '';
            }

            const providerEl = document.getElementById('b-txn-provider');
            if (providerEl) providerEl.textContent = method.toUpperCase();
        }

        // Form Submission
        document.addEventListener('DOMContentLoaded', function () {
            const bookingForm = document.getElementById('booking-form');
            if (!bookingForm) return;

            bookingForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                e.stopPropagation();

                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                if (!paymentMethod) {
                    showToast('Please select a payment method', 'error');
                    return;
                }

                if (paymentMethod.value === 'mtn') {
                    const mtnNumber = document.getElementById('b-mtn-number').value.trim();
                    if (!mtnNumber) {
                        showToast('Please enter your MTN Mobile number', 'error');
                        return;
                    }
                }

                const btn = document.getElementById('confirm-booking-btn');
                const btnText = document.getElementById('btn-text');
                const btnLoading = document.getElementById('btn-loading');

                btn.disabled = true;
                if (btnText) btnText.classList.add('hidden');
                if (btnLoading) {
                    btnLoading.classList.remove('hidden');
                    btnLoading.classList.add('flex');
                }

                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                if (!csrfToken) {
                    showToast('Security error. Please refresh the page.', 'error');
                    btn.disabled = false;
                    if (btnText) btnText.classList.remove('hidden');
                    if (btnLoading) {
                        btnLoading.classList.add('hidden');
                        btnLoading.classList.remove('flex');
                    }
                    return;
                }

                const bookingPayload = {
                    booking_type: 'hotel',
                    facility_id: formData.get('facility_id') || {{ (int) $facility->id }},
                    room_id: formData.get('room_id'),
                    checkin_date: formData.get('checkin_date'),        // ✅ صح
                    checkout_date: formData.get('checkout_date'),      // ✅ صح
                    adults: parseInt(formData.get('adults')),
                    children: parseInt(formData.get('children') || 0),
                    guest_name: `${formData.get('guest_firstname')} ${formData.get('guest_lastname')}`.trim(),
                    guest_firstname: formData.get('guest_firstname'),
                    guest_lastname: formData.get('guest_lastname'),
                    guest_email: formData.get('guest_email'),
                    guest_phone: formData.get('guest_phone'),
                    payment_method: formData.get('payment_method'),
                    payment_phone: formData.get('mtn_number') || formData.get('guest_phone'),
                    total_price: parseFloat(formData.get('total_price_rwf')),
                    total_price_rwf: parseFloat(formData.get('total_price_rwf')),
                    total_price_usd: parseFloat(formData.get('total_price_usd')),
                    nights: parseInt(formData.get('nights'))
                };


                // Show transaction panel
                const txnPanel = document.getElementById('b-transaction-panel');
                const txnBadge = document.getElementById('b-txn-status-badge');
                const txnSpinner = document.getElementById('b-txn-spinner');
                const txnProvider = document.getElementById('b-txn-provider');
                const txnRefEl = document.getElementById('b-txn-ref');

                if (txnPanel) txnPanel.classList.remove('hidden');
                if (txnBadge) {
                    txnBadge.textContent = 'Processing';
                    txnBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700';
                }
                if (txnSpinner) txnSpinner.classList.remove('hidden');
                if (txnProvider) txnProvider.textContent = (paymentMethod.value || '-').toUpperCase();
                if (txnRefEl) txnRefEl.textContent = '-';

                try {
                    const response = await fetch(BOOKINGS_STORE_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(bookingPayload)
                    });

                    let result;
                    const contentType = response.headers.get('content-type') || '';

                    if (contentType.includes('application/json')) {
                        result = await response.json();
                    } else {
                        const text = await response.text();
                        console.error('Unexpected non-JSON response:', text.slice(0, 1000));
                        showToast('Server error: Expected JSON but received HTML.', 'error');
                        btn.disabled = false;
                        if (btnText) btnText.classList.remove('hidden');
                        if (btnLoading) {
                            btnLoading.classList.add('hidden');
                            btnLoading.classList.remove('flex');
                        }
                        return;
                    }

                    if (response.ok && result.success) {
                        const ref = result.transaction_reference || result.payment_reference || result.booking_reference || ('BK-' + Date.now());

                        if (txnBadge) {
                            txnBadge.textContent = 'Paid';
                            txnBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700';
                        }
                        if (txnSpinner) txnSpinner.classList.add('hidden');
                        if (txnRefEl) txnRefEl.textContent = ref;

                        const bookingRef = result.booking_reference || ('BK-' + Date.now());
                        document.getElementById('b-booking-ref').textContent = bookingRef;
                        document.getElementById('b-confirm-name').textContent = `${bookingData.firstname} ${bookingData.lastname}`.trim();
                        document.getElementById('b-confirm-room').textContent = roomData.roomName;
                        document.getElementById('b-confirm-checkin').textContent = bookingData.checkin.toLocaleString('en-US', {
                            year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
                        });
                        document.getElementById('b-confirm-checkout').textContent = bookingData.checkout.toLocaleString('en-US', {
                            year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
                        });
                        document.getElementById('b-confirm-guests').textContent = document.getElementById('b-display-guests').textContent;
                        document.getElementById('b-confirm-total').textContent = document.getElementById('b-display-total').textContent;

                        const confirmPayMethod = document.getElementById('b-confirm-payment-method');
                        const confirmPayStatus = document.getElementById('b-confirm-payment-status');
                        const confirmTxnRef = document.getElementById('b-confirm-txn-ref');

                        if (confirmPayMethod) confirmPayMethod.textContent = (paymentMethod.value || '-').toUpperCase();
                        if (confirmPayStatus) confirmPayStatus.textContent = 'Paid';
                        if (confirmTxnRef) confirmTxnRef.textContent = ref;

                        showToast('Booking confirmed successfully! Reference: ' + bookingRef, 'success');
                        goToBookingStep(5);
                    } else {
                        let errorMessage = result.message || 'Booking failed. Please try again.';
                        if (result.errors) {
                            const errors = Object.values(result.errors).flat();
                            errorMessage = errors.join(', ');
                        }
                        showToast(errorMessage, 'error');

                        if (txnBadge) {
                            txnBadge.textContent = 'Failed';
                            txnBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700';
                        }
                        if (txnSpinner) txnSpinner.classList.add('hidden');

                        btn.disabled = false;
                        if (btnText) btnText.classList.remove('hidden');
                        if (btnLoading) {
                            btnLoading.classList.add('hidden');
                            btnLoading.classList.remove('flex');
                        }
                    }
                } catch (error) {
                    console.error('Network error:', error);
                    showToast('Network error. Please check your connection and try again.', 'error');

                    if (txnBadge) {
                        txnBadge.textContent = 'Network Error';
                        txnBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700';
                    }
                    if (txnSpinner) txnSpinner.classList.add('hidden');

                    btn.disabled = false;
                    if (btnText) btnText.classList.remove('hidden');
                    if (btnLoading) {
                        btnLoading.classList.add('hidden');
                        btnLoading.classList.remove('flex');
                    }
                }
            });
        });

        // Close modals on backdrop click
        const bookingModalEl = document.getElementById('bookingModal');
        if (bookingModalEl) {
            bookingModalEl.addEventListener('click', function (e) {
                if (e.target === this) closeBookingModal();
            });
        }

        const loginModalEl = document.getElementById('loginModal');
        if (loginModalEl) {
            loginModalEl.addEventListener('click', function (e) {
                if (e.target === this) closeLoginModal();
            });
        }

        // Close modals with Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeBookingModal();
                closeLoginModal();
            }
        });

        // Update checkout min
        const checkinInput = document.getElementById('b-checkin');
        if (checkinInput) {
            checkinInput.addEventListener('change', function () {
                const checkoutInput = document.getElementById('b-checkout');
                if (checkoutInput) checkoutInput.min = this.value;
            });
        }
    </script>

    @push('scripts')
        <script src="{{ asset('js/prevent-double-submit.js') }}" defer></script>
    @endpush
@endsection
