@extends('layouts.app')

@section('content')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&family=Sedan+SC&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Quicksand', sans-serif; }
        .sedan-font { font-family: 'Sedan SC', serif; }
        .match-orange { background: #F1E8D7; }
        .primary-orange { background: #F46A06; }
        .text-orange { color: #F46A06; }

        /* Notifications */
        .notification {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 9999;
            animation: slideIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px) scale(0.8);
                opacity: 0;
            }
            to {
                transform: translateX(0) scale(1);
                opacity: 1;
            }
        }

        .notification.fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }

        @keyframes fadeOut {
            to {
                transform: translateX(400px) scale(0.8);
                opacity: 0;
            }
        }

        /* Menu Items */
        .menu-item {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .menu-item:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(244, 106, 6, 0.2);
        }

        /* Cart Button */
        .cart-button {
            transition: all 0.3s ease;
        }

        .cart-button:hover {
            transform: scale(1.1) rotate(5deg);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }

        .cart-pulse {
            animation: pulse 0.5s ease-in-out;
        }

        /* Modal Animations */
        .modal-backdrop {
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            animation: slideUp 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: translateY(100px) scale(0.9);
                opacity: 0;
            }
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        /* Auth Modal Shake */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }

        /* Category Buttons */
        .category-btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            background: rgba(244, 106, 6, 0.1);
            color: #151210ff;
        }

        .category-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.4s, height 0.4s;
        }

        .category-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .category-btn.active {
            background: white;
            color: #F46A06;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 106, 6, 0.3);
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Loading Spinner */
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #F46A06;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Quantity Buttons */
        .qty-btn {
            transition: all 0.2s ease;
        }

        .qty-btn:active {
            transform: scale(0.9);
        }

        /* Gallery Hover */
        .gallery-item {
            overflow: hidden;
            position: relative;
        }

        .gallery-item img {
            transition: transform 0.5s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.2) rotate(2deg);
        }

        /* Success Checkmark Animation */
        @keyframes checkmark {
            0% {
                stroke-dashoffset: 100;
            }
            100% {
                stroke-dashoffset: 0;
            }
        }

        .checkmark {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkmark 0.5s ease-out forwards;
        }

        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <!-- Hero Image -->
    <section class="relative w-full px-4 sm:px-6 lg:px-8 pt-6">
        <div class="relative h-[280px] sm:h-[350px] md:h-[420px] lg:h-[480px] overflow-hidden rounded-3xl border-8 border-orange-100 shadow-2xl">
            @php
                $imagePath = null;
                if($facility->image) {
                    if(file_exists(public_path('storage/' . $facility->image))) {
                        $imagePath = asset('storage/' . $facility->image);
                    } elseif(file_exists(public_path($facility->image))) {
                        $imagePath = asset($facility->image);
                    }
                }
            @endphp

            @if($imagePath)
                <img src="{{ $imagePath }}"
                     alt="{{ $facility->name }}"
                     class="w-full h-full object-cover transition-transform duration-700 hover:scale-110"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-orange-200 to-orange-400 flex items-center justify-center\'><i class=\'fas fa-utensils text-white\' style=\'font-size: 120px; opacity: 0.5;\'></i></div>';">
            @else
                <div class="w-full h-full bg-gradient-to-br from-orange-200 to-orange-400 flex items-center justify-center">
                    <i class="fas fa-utensils text-white" style="font-size: 120px; opacity: 0.5;"></i>
                </div>
            @endif

            <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 bg-black/60 backdrop-blur-sm rounded-2xl px-6 py-3 transform hover:scale-105 transition-transform">
                <p class="text-xs sm:text-sm text-white/90 mb-1">Average meal price</p>
                <p class="text-lg sm:text-2xl font-bold text-white">{{ number_format($facility->menuItems->avg('price') ?? 25000) }} RWF</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <!-- Restaurant Info Card -->
        <div class="match-orange rounded-3xl shadow-2xl p-6 sm:p-12 mb-16 -mt-32 relative z-10 transform hover:shadow-3xl transition-shadow">
            <div class="flex flex-wrap justify-between items-start gap-6 mb-6">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold mb-3">{{ $facility->name }}</h2>
                    <div class="flex items-center gap-2 mb-3">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-orange text-xl"></i>
                        @endfor
                        <span class="text-lg ml-2 text-gray-700">5.0 Stars</span>
                    </div>
                    <div class="flex flex-wrap gap-4 text-gray-700">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-orange"></i>
                            {{ $facility->city ?? 'Location' }}
                        </span>
                        <span class="flex items-center gap-2">
                            <i class="fas fa-chair text-orange"></i>
                            Capacity: {{ $facility->capacity ?? 'N/A' }} seats
                        </span>
                        <span class="flex items-center gap-2">
                            <i class="fas fa-phone text-orange"></i>
                            {{ $facility->contact ?? 'Contact Available' }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('restaurants') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-3 rounded-xl transition-all transform hover:scale-105 flex items-center gap-2 shadow-lg">
                    <i class="fas fa-arrow-left"></i> Back to Restaurants
                </a>
            </div>

            <hr class="border-orange-300 border-2 mb-6">

            <h3 class="text-2xl sm:text-3xl font-bold text-orange mb-4 flex items-center gap-3">
                <i class="fas fa-info-circle"></i> About Our Restaurant
            </h3>

            <p class="sedan-font text-lg sm:text-xl leading-relaxed text-gray-800">
                {{ $facility->description ?? 'Welcome to our restaurant. We offer premium dining experience with authentic cuisine and exceptional service.' }}
            </p>
        </div>

        <!-- Services Section -->
        <div class="mb-16">
            <h3 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i class="fas fa-concierge-bell text-orange"></i> What We Offer
            </h3>
            <hr class="border-gray-900 border-2 mb-12">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                        'Private Dining' => 'fa-door-closed',
                        'Takeaway' => 'fa-shopping-bag',
                        'Delivery' => 'fa-truck',
                        'Kids Menu' => 'fa-child',
                        'Vegetarian Options' => 'fa-leaf',
                        'Bar Service' => 'fa-wine-glass',
                        'Air Conditioning' => 'fa-snowflake',
                        'Wheelchair Access' => 'fa-wheelchair',
                        'Pet Friendly' => 'fa-paw',
                        'Valet Parking' => 'fa-key',
                    ];
                @endphp
                @forelse($facility->services as $service)
                    <div class="bg-white border-2 border-orange-200 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2">
                        <div class="flex items-center gap-4">
                            <div class="bg-gradient-to-br from-orange-500 to-orange-600 w-14 h-14 rounded-xl flex items-center justify-center text-white text-2xl shadow-lg transform hover:rotate-12 transition-transform">
                                <i class="fas {{ $serviceIcons[$service->name] ?? 'fa-check-circle' }}"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">{{ $service->name }}</h4>
                                <p class="text-sm text-gray-600">Available service</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-8">
                        <i class="fas fa-info-circle text-4xl mb-3"></i>
                        <p>No services available</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Explore Menu Button -->
        <div class="text-center mb-12">
            <button onclick="document.getElementById('menu-section').scrollIntoView({behavior: 'smooth'})"
                    class="primary-orange text-white text-2xl sm:text-3xl font-bold px-12 sm:px-16 py-5 sm:py-6 rounded-2xl shadow-2xl hover:bg-orange-600 transition-all transform hover:scale-105 flex items-center gap-3 mx-auto">
                <i class="fas fa-book-open"></i> Explore Our Menu
            </button>
        </div>

        <!-- Menu Section -->
        <div id="menu-section" class="mb-16">
            <h3 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i class="fas fa-clipboard-list text-orange"></i> Our Menu
            </h3>
            <hr class="border-gray-900 border-2 mb-8">

              <!-- Category Filter -->
            <div class="match-orange rounded-xl p-4 mb-8 flex flex-wrap justify-center gap-3 shadow-lg">
                <button onclick="filterMenu('all')"
                        class="category-btn active px-6 py-3 rounded-lg font-bold text-lg flex items-center gap-2 relative z-10">
                    <i class="fas fa-th-large"></i> All Items
                </button>
                <button onclick="filterMenu('Appetizers')"
                        class="category-btn px-6 py-3 rounded-lg font-bold text-lg flex items-center gap-2 relative z-10">
                    <i class="fas fa-cheese"></i> Appetizers
                </button>
                <button onclick="filterMenu('Main Courses')"
                        class="category-btn px-6 py-3 rounded-lg font-bold text-lg flex items-center gap-2 relative z-10">
                    <i class="fas fa-drumstick-bite"></i> Main Courses
                </button>
                <button onclick="filterMenu('Desserts')"
                        class="category-btn px-6 py-3 rounded-lg font-bold text-lg flex items-center gap-2 relative z-10">
                    <i class="fas fa-ice-cream"></i> Desserts
                </button>
                <button onclick="filterMenu('Beverages')"
                        class="category-btn px-6 py-3 rounded-lg font-bold text-lg flex items-center gap-2 relative z-10">
                    <i class="fas fa-glass-cheers"></i> Beverages
                </button>
                <button onclick="filterMenu('Specials')"
                        class="category-btn px-6 py-3 rounded-lg font-bold text-lg flex items-center gap-2 relative z-10">
                    <i class="fas fa-star"></i> Chef's Specials
                </button>
            </div>


            <!-- Shopping Cart Icon -->
            <div class="fixed top-28 sm:top-32 right-4 sm:right-8 z-50">
                <div class="relative">
                    <button onclick="toggleCart()"
                            class="cart-button bg-gradient-to-br from-orange-500 to-orange-600 w-14 h-14 sm:w-16 sm:h-16 rounded-full shadow-2xl flex items-center justify-center text-white">
                        <i class="fas fa-shopping-cart text-xl sm:text-2xl"></i>
                    </button>
                    <div class="absolute -top-2 -right-2 bg-red-500 border-2 border-white rounded-full w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center">
                        <span id="cart-count" class="text-white font-bold text-xs sm:text-sm">0</span>
                    </div>
                </div>
            </div>

            <!-- Menu Items Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="menu-items-container">
                @forelse($facility->menuItems as $item)
                    <div class="menu-item bg-white rounded-xl shadow-lg overflow-hidden" data-category="{{ $item->category }}">
                        @php
                            $imagePath = null;
                            if($item->image) {
                                if(file_exists(public_path('storage/' . $item->image))) {
                                    $imagePath = asset('storage/' . $item->image);
                                } elseif(file_exists(public_path($item->image))) {
                                    $imagePath = asset($item->image);
                                }
                            }
                        @endphp

                        @if($imagePath)
                            <img src="{{ $imagePath }}"
                                 alt="{{ $item->name }}"
                                 class="w-full h-48 object-cover"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-48 bg-gradient-to-br from-orange-200 to-orange-300 flex items-center justify-center\'><i class=\'fas fa-utensils text-white text-6xl opacity-50\'></i></div>';">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-orange-200 to-orange-300 flex items-center justify-center">
                                <i class="fas fa-utensils text-white text-6xl opacity-50"></i>
                            </div>
                        @endif

                        <div class="p-6">
                            <h4 class="text-xl sm:text-2xl font-bold mb-3 text-gray-900">{{ $item->name }}</h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $item->description ?? 'Delicious meal from our kitchen' }}</p>

                            <div class="flex items-center gap-3 sm:gap-4 mb-4 bg-gray-50 rounded-lg p-3">
                                <span class="text-sm sm:text-base font-bold text-gray-700">Qty:</span>
                                <button onclick="decreaseQty({{ $item->id }})"
                                        class="qty-btn w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-bold text-lg sm:text-xl">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span id="qty-{{ $item->id }}" class="text-xl sm:text-2xl font-bold text-orange-600 min-w-[30px] sm:min-w-[40px] text-center">1</span>
                                <button onclick="increaseQty({{ $item->id }})"
                                        class="qty-btn w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-bold text-lg sm:text-xl">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="primary-orange text-white rounded-xl py-3 text-center flex items-center justify-center gap-2">
                                    <i class="fas fa-tag"></i>
                                    <span class="text-base sm:text-lg font-bold">{{ number_format($item->price) }} RWF</span>
                                </div>
                                <button onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})"
                                        class="primary-orange text-white rounded-xl py-3 text-base sm:text-lg font-bold hover:bg-orange-600 transition-all transform hover:scale-105 flex items-center justify-center gap-2">
                                    <i class="fas fa-cart-plus"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-12">
                        <i class="fas fa-utensils text-6xl mb-4 opacity-30"></i>
                        <p class="text-xl font-semibold">No menu items available</p>
                        <p class="text-sm">Check back soon for our delicious offerings!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Gallery Section -->
        <div class="mb-16">
            <h3 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i class="fas fa-images text-orange"></i> Restaurant Gallery
            </h3>
            <hr class="border-gray-900 border-2 mb-12">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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

                    <div class="gallery-item relative group overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all">
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
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <i class="fas fa-search-plus text-white text-4xl"></i>
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
    <footer class="bg-gray-900 text-white py-8 text-center">
        <p class="text-lg flex items-center justify-center gap-2">
            <i class="far fa-copyright"></i> 2024 Restaurant Management System. All Rights Reserved
        </p>
    </footer>

    <!-- Auth Required Modal -->
    <div id="auth-modal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-[70] flex items-center justify-center p-4 modal-backdrop">
        <div class="modal-content bg-white rounded-3xl p-8 max-w-md w-full text-center shadow-2xl">
            <div class="mb-6">
                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-orange-500 text-5xl"></i>
                </div>
                <h3 class="text-3xl font-bold mb-3 text-gray-900">Login Required</h3>
                <p class="text-gray-600 text-lg leading-relaxed">
                    You need to login to place an order. Create an account or login to enjoy our delicious meals!
                </p>
            </div>

            <div class="space-y-3 mb-6">
                <a href="{{ route('login') }}"
                   class="block w-full primary-orange text-white py-4 rounded-xl font-bold text-lg hover:bg-orange-600 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login Now
                </a>
                <a href="{{ route('register') }}"
                   class="block w-full bg-gray-100 text-gray-900 py-4 rounded-xl font-bold text-lg hover:bg-gray-200 transition-all transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i> Create Account
                </a>
            </div>

            <button onclick="closeAuthModal()"
                    class="text-gray-500 hover:text-gray-700 font-semibold transition">
                Maybe Later
            </button>
        </div>
    </div>

    <!-- Cart Modal -->
    <div id="cart-modal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-[60] flex items-center justify-center p-4 modal-backdrop">
        <div class="modal-content bg-white rounded-2xl p-6 sm:p-8 max-w-3xl w-full max-h-[85vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl sm:text-3xl font-bold flex items-center gap-3">
                    <i class="fas fa-shopping-cart text-orange"></i> Your Order Summary
                </h3>
                <button onclick="toggleCart()" class="text-3xl sm:text-4xl text-gray-400 hover:text-orange transition transform hover:rotate-90">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="cart-items" class="space-y-3 mb-6">
                <!-- Cart items will be inserted here -->
            </div>

            <div class="border-t-2 pt-6">
                <div class="bg-orange-50 rounded-xl p-4 mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-base sm:text-lg text-gray-700">Subtotal:</span>
                        <span id="cart-subtotal" class="text-lg sm:text-xl font-bold text-gray-900">0 RWF</span>
                    </div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-base sm:text-lg text-gray-700">Service Fee (5%):</span>
                        <span id="cart-service-fee" class="text-lg sm:text-xl font-bold text-gray-900">0 RWF</span>
                    </div>
                    <hr class="my-3 border-orange-200">
                    <div class="flex justify-between items-center">
                        <span class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-receipt text-orange"></i> Total:
                        </span>
                        <span id="cart-total" class="text-2xl sm:text-3xl font-bold text-orange">0 RWF</span>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <h4 class="text-lg sm:text-xl font-bold mb-3 flex items-center gap-2">
                        <i class="fas fa-sticky-note text-orange"></i> Special Instructions
                    </h4>
                    <textarea id="order-notes" placeholder="Any special requests? (allergies, preferences, etc.)"
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-orange-500 focus:outline-none transition h-24 resize-none"></textarea>
                </div>

                <button onclick="proceedToCheckout()"
                        id="checkout-btn"
                        class="primary-orange text-white w-full py-4 rounded-xl text-lg sm:text-xl font-bold hover:bg-orange-600 transition-all transform hover:scale-105 flex items-center justify-center gap-3 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-check-circle"></i> Confirm Order
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-[80] flex items-center justify-center p-4 modal-backdrop">
        <div class="modal-content bg-white rounded-2xl p-8 max-w-md w-full text-center">
            <div class="mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12" viewBox="0 0 52 52">
                        <circle class="checkmark" cx="26" cy="26" r="25" fill="none" stroke="#22c55e" stroke-width="3"/>
                        <path class="checkmark" fill="none" stroke="#22c55e" stroke-width="3" d="M14 27l7 7 16-16"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-2 text-gray-900">Order Confirmed!</h3>
                <p class="text-gray-600">Your order has been successfully placed.</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 mb-6 text-left">
                <p class="text-sm text-gray-600 mb-2">Order Reference:</p>
                <p id="order-reference" class="text-2xl font-bold text-orange break-all">#</p>
            </div>
            <button onclick="closeSuccessModal()" class="primary-orange text-white w-full py-3 rounded-xl font-bold hover:bg-orange-600 transition-all transform hover:scale-105">
                Close
            </button>
        </div>
    </div>

    <script>
    // Check if user is authenticated
    const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
    const csrfToken = '{{ csrf_token() }}';
    const facilityId = {{ $facility->id }};
    const bookingStoreUrl = '{{ route("bookings.store") }}';

    let cart = {};
    let isSubmitting = false;

    // Filter Menu
    function filterMenu(category) {
        const items = document.querySelectorAll('.menu-item');
        const buttons = document.querySelectorAll('.category-btn');

        buttons.forEach(btn => {
            btn.classList.remove('active');
        });

        // Find and activate the clicked button
        buttons.forEach(btn => {
            const btnText = btn.textContent.trim();
            if (category === 'all' && btnText.includes('All Items')) {
                btn.classList.add('active');
            } else if (btnText.includes(category)) {
                btn.classList.add('active');
            }
        });

        items.forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            if (category === 'all' || itemCategory === category) {
                item.style.display = 'block';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, 10);
            } else {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    item.style.display = 'none';
                }, 300);
            }
        });
    }

    // Quantity Controls
    function increaseQty(itemId) {
        const qtyEl = document.getElementById(`qty-${itemId}`);
        if (qtyEl) {
            let qty = parseInt(qtyEl.textContent) || 1;
            qtyEl.textContent = qty + 1;
        }
    }

    function decreaseQty(itemId) {
        const qtyEl = document.getElementById(`qty-${itemId}`);
        if (qtyEl) {
            let qty = parseInt(qtyEl.textContent) || 1;
            if (qty > 1) {
                qtyEl.textContent = qty - 1;
            }
        }
    }

    // Add to Cart
    function addToCart(itemId, itemName, itemPrice) {
        // Check authentication first
        if (!isAuthenticated) {
            showAuthModal();
            return;
        }

        const qtyEl = document.getElementById(`qty-${itemId}`);
        if (!qtyEl) return;

        const qty = parseInt(qtyEl.textContent) || 1;

        if (cart[itemId]) {
            cart[itemId].quantity += qty;
        } else {
            cart[itemId] = {
                name: itemName,
                price: itemPrice,
                quantity: qty
            };
        }

        updateCartDisplay();
        qtyEl.textContent = '1';

        // Animate cart button
        const cartBtn = document.querySelector('.cart-button');
        if (cartBtn) {
            cartBtn.classList.add('cart-pulse');
            setTimeout(() => cartBtn.classList.remove('cart-pulse'), 500);
        }

        showNotification(`${itemName} added to cart!`, 'success');
    }

    // Remove from Cart
    function removeFromCart(itemId) {
        if (cart[itemId]) {
            const name = cart[itemId].name;
            delete cart[itemId];
            updateCartDisplay();
            showNotification(`${name} removed from cart`, 'info');
        }
    }

    // Update Cart Display
    function updateCartDisplay() {
        const cartCount = document.getElementById('cart-count');
        const cartItems = document.getElementById('cart-items');
        const cartSubtotal = document.getElementById('cart-subtotal');
        const cartServiceFee = document.getElementById('cart-service-fee');
        const cartTotal = document.getElementById('cart-total');

        let totalItems = 0, subtotal = 0, html = '';

        for (const [id, item] of Object.entries(cart)) {
            totalItems += item.quantity;
            subtotal += item.price * item.quantity;

            html += `
                <div class="flex justify-between items-center p-4 bg-orange-50 rounded-lg border-l-4 border-orange-500 transform hover:scale-102 transition">
                    <div class="flex-1">
                        <h4 class="font-bold text-base sm:text-lg text-gray-900">${escapeHtml(item.name)}</h4>
                        <p class="text-gray-600 text-sm">
                            Qty: ${item.quantity} × ${formatNumber(item.price)} RWF
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-orange text-base sm:text-lg">${formatNumber(item.price * item.quantity)} RWF</span>
                        <button onclick="removeFromCart(${id})" class="text-red-600 hover:text-red-800 transition transform hover:scale-110">
                            <i class="fas fa-trash text-lg sm:text-xl"></i>
                        </button>
                    </div>
                </div>
            `;
        }

        const serviceFee = Math.round(subtotal * 0.05);
        const total = subtotal + serviceFee;

        if (cartCount) cartCount.textContent = totalItems;
        if (cartItems) {
            cartItems.innerHTML = html || '<p class="text-center text-gray-500 py-6"><i class="fas fa-shopping-cart text-4xl mb-2 opacity-30"></i><br>Your cart is empty</p>';
        }
        if (cartSubtotal) cartSubtotal.textContent = formatNumber(subtotal) + ' RWF';
        if (cartServiceFee) cartServiceFee.textContent = formatNumber(serviceFee) + ' RWF';
        if (cartTotal) cartTotal.textContent = formatNumber(total) + ' RWF';
    }

    // Toggle Cart Modal
    function toggleCart() {
        if (!isAuthenticated) {
            showAuthModal();
            return;
        }

        const modal = document.getElementById('cart-modal');
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }

    // Show Auth Modal
    function showAuthModal() {
        const modal = document.getElementById('auth-modal');
        if (modal) {
            modal.classList.remove('hidden');

            // Shake animation
            const content = modal.querySelector('.modal-content');
            if (content) {
                content.classList.add('shake');
                setTimeout(() => content.classList.remove('shake'), 500);
            }
        }
    }

    // Close Auth Modal
    function closeAuthModal() {
        const modal = document.getElementById('auth-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Proceed to Checkout
    async function proceedToCheckout() {
        if (isSubmitting) {
            showNotification('Please wait...', 'info');
            return;
        }

        if (Object.keys(cart).length === 0) {
            showNotification('Your cart is empty!', 'error');
            return;
        }

        if (!isAuthenticated) {
            showAuthModal();
            return;
        }

        isSubmitting = true;
        const checkoutBtn = document.getElementById('checkout-btn');
        const originalText = checkoutBtn ? checkoutBtn.innerHTML : '';

        if (checkoutBtn) {
            checkoutBtn.disabled = true;
            checkoutBtn.innerHTML = '<div class="spinner mx-auto"></div>';
        }

        const notesEl = document.getElementById('order-notes');
        const notes = notesEl ? notesEl.value.trim() : '';

        // Calculate totals
        let subtotal = 0;
        for (const item of Object.values(cart)) {
            subtotal += item.price * item.quantity;
        }
        const serviceFee = Math.round(subtotal * 0.05);
        const total = subtotal + serviceFee;

        // Prepare order data
        const orderData = {
            _token: csrfToken,
            facility_id: facilityId,
            booking_type: 'restaurant',
            order_items: JSON.stringify(cart),
            special_requests: notes,
            total_price: total,
            total_price_rwf: total,
            total_price_usd: (total / 1000).toFixed(2),
            number_of_guests: Object.values(cart).reduce((sum, item) => sum + item.quantity, 0)
        };

        try {
            const response = await fetch(bookingStoreUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(orderData)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Success!
                const orderRefEl = document.getElementById('order-reference');
                if (orderRefEl) {
                    orderRefEl.textContent = data.booking_reference || '#ORD-' + Date.now();
                }

                const successModal = document.getElementById('success-modal');
                if (successModal) {
                    successModal.classList.remove('hidden');
                }

                // Reset everything
                cart = {};
                updateCartDisplay();
                toggleCart();
                if (notesEl) notesEl.value = '';

                showNotification('Order placed successfully!', 'success');
            } else {
                throw new Error(data.message || 'Failed to place order');
            }
        } catch (error) {
            console.error('Order Error:', error);
            showNotification(error.message || 'Failed to place order. Please try again.', 'error');
        } finally {
            isSubmitting = false;
            if (checkoutBtn) {
                checkoutBtn.disabled = false;
                checkoutBtn.innerHTML = originalText;
            }
        }
    }

    // Close Success Modal
    function closeSuccessModal() {
        const modal = document.getElementById('success-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Show Notification
    function showNotification(message, type = 'success') {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500'
        };

        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };

        const div = document.createElement('div');
        div.className = `notification ${colors[type]} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 max-w-md`;
        div.innerHTML = `
            <i class="fas ${icons[type]} text-2xl"></i>
            <span class="flex-1 font-semibold">${escapeHtml(message)}</span>
        `;
        document.body.appendChild(div);

        setTimeout(() => {
            div.classList.add('fade-out');
            setTimeout(() => div.remove(), 300);
        }, 3000);
    }

    // Helper Functions
    function formatNumber(num) {
        return new Intl.NumberFormat('en-US').format(Math.round(num));
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Close modals on backdrop click
    document.addEventListener('click', function(e) {
        const authModal = document.getElementById('auth-modal');
        const cartModal = document.getElementById('cart-modal');
        const successModal = document.getElementById('success-modal');

        if (e.target === authModal) {
            closeAuthModal();
        }
        if (e.target === cartModal) {
            toggleCart();
        }
        if (e.target === successModal) {
            closeSuccessModal();
        }
    });

    // Prevent modal content clicks from closing modal
    document.querySelectorAll('.modal-content').forEach(content => {
        content.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // Initialize on load
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Restaurant Order System Initialized');
        console.log('Authenticated:', isAuthenticated);
        updateCartDisplay();
    });
    </script>
@endsection


