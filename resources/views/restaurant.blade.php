@extends('layouts.dashboard')

@section('title', 'Restaurant Management')
@section('header', 'Restaurant Management')

@section('content')
<div class="space-y-6">
    <!-- Navigation Tabs -->
    <nav class="flex flex-wrap gap-2 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
        <a href="{{ route('dashboard.hotel') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition font-semibold
           {{ request()->routeIs('dashboard.hotel') ? 'bg-[#F46A06] text-white' : 'text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20' }}">
            <i class="fas fa-hotel w-5 h-5"></i> Hotels
        </a>

        <a href="{{ route('dashboard.restaurant') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition font-semibold
           {{ request()->routeIs('dashboard.restaurant') ? 'bg-[#F46A06] text-white' : 'text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20' }}">
            <i class="fas fa-utensils w-5 h-5"></i> Restaurants
        </a>

        <a href="{{ route('dashboard.coffee') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition font-semibold
           {{ request()->routeIs('dashboard.coffee') ? 'bg-[#F46A06] text-white' : 'text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20' }}">
            <i class="fas fa-mug-hot w-5 h-5"></i> Coffee Shops
        </a>

        <a href="{{ route('dashboard.payment') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition font-semibold
           {{ request()->routeIs('dashboard.payment') ? 'bg-[#F46A06] text-white' : 'text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20' }}">
            <i class="fas fa-credit-card w-5 h-5"></i> Payments
        </a>

        <a href="{{ route('dashboard.transport') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition font-semibold
           {{ request()->routeIs('dashboard.transport') ? 'bg-[#F46A06] text-white' : 'text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20' }}">
            <i class="fas fa-car w-5 h-5"></i> Transportation
        </a>
    </nav>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#F46A06] to-orange-600 dark:from-[#d85e05] dark:to-orange-700 rounded-lg shadow-xl p-6 text-white border border-orange-500/20">
        <h1 class="text-4xl font-bold mb-2 flex items-center gap-3">
            <i class="fas fa-utensils"></i>
            Restaurant Management
        </h1>
        <p class="text-orange-100 dark:text-orange-200 text-lg">Manage all your restaurants, menus, and operations</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-400 p-4 rounded-lg shadow">
            <p class="text-green-700 dark:text-green-400 font-semibold flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </p>
        </div>
    @endif

    <!-- Add Restaurant Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 border border-gray-200 dark:border-gray-700">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 border-b-2 border-[#F46A06] pb-3 flex items-center gap-2">
            <i class="fas fa-plus-circle text-[#F46A06]"></i>
            Add New Restaurant
        </h2>

        <form action="{{ route('dashboard.restaurant.store') }}" method="POST"
              class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
              enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="Restaurant">

            <div class="flex flex-col">
                <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Restaurant Name *</label>
                <input type="text" name="name" placeholder="Enter restaurant name" required
                       class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition placeholder-gray-400 dark:placeholder-gray-500">
            </div>

            <div class="flex flex-col">
                <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">City *</label>
                <input type="text" name="city" placeholder="Enter city" required
                       class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition placeholder-gray-400 dark:placeholder-gray-500">
            </div>

            <div class="flex flex-col">
                <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Seating Capacity</label>
                <input type="number" name="capacity" placeholder="Number of seats" min="1"
                       class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition placeholder-gray-400 dark:placeholder-gray-500">
            </div>

            <div class="flex flex-col">
                <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Manager Name *</label>
                <input type="text" name="manager" placeholder="Manager full name" required
                       class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition placeholder-gray-400 dark:placeholder-gray-500">
            </div>

            <div class="flex flex-col">
                <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Contact Email *</label>
                <input type="email" name="contact" placeholder="manager@restaurant.com" required
                       class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition placeholder-gray-400 dark:placeholder-gray-500">
            </div>

            <div class="flex flex-col">
                <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Restaurant Image</label>
                <input type="file" name="image" accept="image/*"
                       class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#F46A06] file:text-white hover:file:bg-orange-600 file:cursor-pointer">
            </div>

            <button type="submit" class="lg:col-span-3 bg-[#F46A06] hover:bg-orange-600 dark:bg-[#ff8c42] dark:hover:bg-[#F46A06] text-white font-bold py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Add Restaurant
            </button>
        </form>
    </div>

    <!-- Restaurants Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="bg-[#F46A06] dark:bg-[#d85e05] text-white p-6">
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i class="fas fa-list"></i> Restaurants List
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 border-b-2 border-gray-300 dark:border-gray-600">
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-200">Restaurant Name</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-200">City</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-200">Seating</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-200">Manager</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-200">Email</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facilities as $index => $restaurant)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800 dark:text-gray-200">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">{{ $restaurant->name }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $restaurant->city }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $restaurant->capacity ?? '-' }} seats</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $restaurant->manager }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300 text-sm">{{ $restaurant->contact }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-3">
                                   <a href="{{ route('dashboard.restaurant.edit', $restaurant->id) }}"
                                       class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition font-semibold shadow hover:shadow-lg">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                   <form action="{{ route('dashboard.restaurant.delete', $restaurant->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this restaurant?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-lg transition font-semibold flex items-center gap-1 shadow hover:shadow-lg">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-8 text-lg">
                                <i class="fas fa-info-circle text-3xl mb-2 block"></i>
                                <p>No restaurants found. Create your first restaurant above!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-lg shadow-xl p-6 text-white border border-blue-400/20 hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold opacity-90">Total Restaurants</h3>
                    <p class="text-4xl font-bold mt-2">{{ $facilities->count() }}</p>
                </div>
                <i class="fas fa-utensils text-5xl opacity-20"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 rounded-lg shadow-xl p-6 text-white border border-green-400/20 hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold opacity-90">Cities Covered</h3>
                    <p class="text-4xl font-bold mt-2">{{ $facilities->groupBy('city')->count() }}</p>
                </div>
                <i class="fas fa-map-marker-alt text-5xl opacity-20"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 rounded-lg shadow-xl p-6 text-white border border-purple-400/20 hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold opacity-90">Total Capacity</h3>
                    <p class="text-4xl font-bold mt-2">{{ $facilities->sum('capacity') }} <span class="text-lg">seats</span></p>
                </div>
                <i class="fas fa-chair text-5xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Menu Management Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 border border-gray-200 dark:border-gray-700">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 border-b-2 border-[#F46A06] pb-3 flex items-center gap-2">
            <i class="fas fa-book text-[#F46A06]"></i>
            Menu Management
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="flex flex-col">
                <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Select Restaurant</label>
                <select id="restaurant-select" class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition">
                    <option value="">Choose a restaurant...</option>
                    @foreach($facilities as $restaurant)
                        <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

       <form id="menu-form" method="POST" action="{{ route('dashboard.menu.store') }}" enctype="multipart/form-data" class="hidden">
            @csrf
            <input type="hidden" name="facility_id" id="facility_id">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="flex flex-col">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Dish Name *</label>
                    <input type="text" name="name" placeholder="Enter dish name" required
                           class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <div class="flex flex-col">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Category *</label>
                    <select name="category" required class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition">
                        <option value="">Select category</option>
                        <option value="Appetizers">Appetizers</option>
                        <option value="Main Courses">Main Courses</option>
                        <option value="Desserts">Desserts</option>
                        <option value="Beverages">Beverages</option>
                        <option value="Specials">Chef's Specials</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Price (RWF) *</label>
                    <input type="number" name="price" placeholder="Enter price" min="0" step="100" required
                           class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <div class="flex flex-col md:col-span-2">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Description</label>
                    <textarea name="description" placeholder="Dish description" rows="3"
                              class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition resize-none placeholder-gray-400 dark:placeholder-gray-500"></textarea>
                </div>

                <div class="flex flex-col">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Dish Image</label>
                    <input type="file" name="image" accept="image/*"
                           class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#F46A06] file:text-white hover:file:bg-orange-600 file:cursor-pointer">
                </div>
            </div>

            <button type="submit" class="bg-[#F46A06] hover:bg-orange-600 dark:bg-[#ff8c42] dark:hover:bg-[#F46A06] text-white font-bold py-3 px-8 rounded-lg transition shadow-lg hover:shadow-xl flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Menu Item
            </button>
        </form>
    </div>

    <!-- Menu Items List -->
    <div id="menu-items-section" class="hidden bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="bg-[#F46A06] dark:bg-[#d85e05] text-white p-6">
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i class="fas fa-list-ul"></i> Menu Items
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 border-b-2 border-gray-300 dark:border-gray-600">
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-200">Dish Name</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-200">Category</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-200">Price</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-200">Description</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody id="menu-items-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Menu items will be loaded here dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Services Management Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 border border-gray-200 dark:border-gray-700">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 border-b-2 border-[#F46A06] pb-3 flex items-center gap-2">
            <i class="fas fa-concierge-bell text-[#F46A06]"></i>
            Restaurant Services
        </h2>

        <form method="POST" action="{{ route('dashboard.service.store') }}" class="mb-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Select Restaurant</label>
                    <select name="facility_id" required class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition">
                        <option value="">Choose a restaurant...</option>
                        @foreach($facilities as $restaurant)
                            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Service Name *</label>
                    <input type="text" name="name" placeholder="e.g., Free WiFi, Parking, Live Music" required
                           class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <button type="submit" class="bg-[#F46A06] hover:bg-orange-600 dark:bg-[#ff8c42] dark:hover:bg-[#F46A06] text-white font-bold py-3 rounded-lg transition shadow-lg hover:shadow-xl mt-auto flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i> Add Service
                </button>
            </div>
        </form>

        <div id="services-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Services will be loaded here dynamically -->
        </div>
    </div>

    <!-- Gallery Management Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 border border-gray-200 dark:border-gray-700">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 border-b-2 border-[#F46A06] pb-3 flex items-center gap-2">
            <i class="fas fa-images text-[#F46A06]"></i>
            Gallery Management
        </h2>

        <form method="POST" action="{{ route('dashboard.gallery.store') }}" enctype="multipart/form-data" class="mb-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Select Restaurant</label>
                    <select name="facility_id" required class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition">
                        <option value="">Choose a restaurant...</option>
                        @foreach($facilities as $restaurant)
                            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Upload Image *</label>
                    <input type="file" name="image" accept="image/*" required
                           class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#F46A06] file:text-white hover:file:bg-orange-600 file:cursor-pointer">
                </div>

                <button type="submit" class="bg-[#F46A06] hover:bg-orange-600 dark:bg-[#ff8c42] dark:hover:bg-[#F46A06] text-white font-bold py-3 rounded-lg transition shadow-lg hover:shadow-xl mt-auto flex items-center justify-center gap-2">
                    <i class="fas fa-upload"></i> Upload Image
                </button>
            </div>
        </form>

        <div id="gallery-preview" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <!-- Gallery images will be loaded here dynamically -->
        </div>
    </div>

    <!-- Restaurant Details Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 border border-gray-200 dark:border-gray-700">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 border-b-2 border-[#F46A06] pb-3 flex items-center gap-2">
            <i class="fas fa-edit text-[#F46A06]"></i>
            Edit Restaurant Details
        </h2>

        <select id="detail-restaurant" class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition w-full md:w-1/3 mb-6">
            <option value="">Select restaurant to edit...</option>
            @foreach($facilities as $restaurant)
                <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
            @endforeach
        </select>

        <form id="detail-form" method="POST" enctype="multipart/form-data" class="hidden space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Restaurant Name</label>
                    <input type="text" name="name" id="detail-name" class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition">
                </div>

                <div class="flex flex-col">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">City</label>
                    <input type="text" name="city" id="detail-city" class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition">
                </div>

                <div class="flex flex-col">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Capacity (Seats)</label>
                    <input type="number" name="capacity" id="detail-capacity" class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition">
                </div>

                <div class="flex flex-col">
                    <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Manager</label>
                    <input type="text" name="manager" id="detail-manager" class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition">
                </div>
            </div>

            <div class="flex flex-col">
                <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Contact Email</label>
                <input type="email" name="contact" id="detail-contact" class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition">
            </div>

            <div class="flex flex-col">
                <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Description</label>
                <textarea name="description" id="detail-description" rows="5" class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition resize-none"></textarea>
            </div>

            <div class="flex flex-col">
                <label class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Restaurant Image</label>
                <input type="file" name="image" accept="image/*" class="border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#F46A06] dark:focus:border-[#ff8c42] transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-500 file:text-white hover:file:bg-green-600 file:cursor-pointer">
            </div>

            <button type="submit" class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition shadow-lg hover:shadow-xl flex items-center gap-2">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Restaurant selector for menu management
    document.getElementById('restaurant-select').addEventListener('change', function(e) {
        const restaurantId = e.target.value;
        if (restaurantId) {
            document.getElementById('menu-form').classList.remove('hidden');
            document.getElementById('facility_id').value = restaurantId;
            loadMenuItems(restaurantId);
        } else {
            document.getElementById('menu-form').classList.add('hidden');
            document.getElementById('menu-items-section').classList.add('hidden');
        }
    });

    // Restaurant selector for details
    document.getElementById('detail-restaurant').addEventListener('change', function(e) {
        const restaurantId = e.target.value;
        if (restaurantId) {
            loadRestaurantDetails(restaurantId);
        } else {
            document.getElementById('detail-form').classList.add('hidden');
        }
    });

    function loadMenuItems(restaurantId) {
        // This would typically load from your backend via AJAX
        document.getElementById('menu-items-section').classList.remove('hidden');

        // Example: Fetch menu items
        // fetch(`/dashboard/restaurant/${restaurantId}/menu`)
        //     .then(response => response.json())
        //     .then(data => {
        //         // Populate menu items table
        //     });
    }

    function loadRestaurantDetails(restaurantId) {
        // This would typically load from your backend via AJAX
        document.getElementById('detail-form').classList.remove('hidden');
        document.getElementById('detail-form').action = `/dashboard/restaurant/${restaurantId}`;

        // Example: Fetch restaurant details
        // fetch(`/dashboard/restaurant/${restaurantId}`)
        //     .then(response => response.json())
        //     .then(data => {
        //         document.getElementById('detail-name').value = data.name;
        //         document.getElementById('detail-city').value = data.city;
        //         document.getElementById('detail-capacity').value = data.capacity;
        //         document.getElementById('detail-manager').value = data.manager;
        //         document.getElementById('detail-contact').value = data.contact;
        //         document.getElementById('detail-description').value = data.description;
        //     });
    }

    // Add smooth transitions for form reveals
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                const originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                submitBtn.disabled = true;

                // Re-enable after a timeout if form validation fails
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = originalContent;
                        submitBtn.disabled = false;
                    }
                }, 3000);
            }
        });
    });

    // Add animation to stats cards
    const statsCards = document.querySelectorAll('.grid > div');
    statsCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });

    // Confirm delete with better UX
    document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (confirm('⚠️ Are you sure you want to delete this restaurant?\n\nThis action cannot be undone and will also delete all associated menus, services, and gallery images.')) {
                this.submit();
            }
        });
        form.removeAttribute('onsubmit');
    });

    // Auto-hide success messages after 5 seconds
    setTimeout(() => {
        const successMsg = document.querySelector('.bg-green-50, .dark\\:bg-green-900\\/20');
        if (successMsg) {
            successMsg.style.transition = 'all 0.5s ease';
            successMsg.style.opacity = '0';
            successMsg.style.transform = 'translateX(100%)';
            setTimeout(() => successMsg.remove(), 500);
        }
    }, 5000);

    console.log('%c🍽️ Restaurant Dashboard Loaded Successfully!', 'color: #F46A06; font-size: 16px; font-weight: bold;');
</script>
@endpush

@push('styles')
<style>
    /* Custom animations for dark mode transitions */
    * {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }

    /* Hover effects for cards */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(244, 106, 6, 0.2);
    }

    .dark .hover-lift:hover {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
    }

    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* File input styling */
    input[type="file"]::file-selector-button {
        transition: all 0.3s ease;
    }

    /* Table row hover effect */
    tbody tr {
        transition: all 0.2s ease;
    }

    /* Loading animation */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .fa-spin {
        animation: spin 1s linear infinite;
    }

    /* Fade in animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    /* Scale animation for stats */
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .scale-in {
        animation: scaleIn 0.4s ease-out;
    }

    /* Better focus states */
    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 3px rgba(244, 106, 6, 0.1);
    }

    .dark input:focus, .dark select:focus, .dark textarea:focus {
        box-shadow: 0 0 0 3px rgba(255, 140, 66, 0.2);
    }
</style>
@endpush
@endsection
