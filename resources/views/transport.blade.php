@extends('layouts.dashboard')

@section('content')

<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#F37021] to-orange-600 dark:from-[#d85e05] dark:to-orange-700 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 opacity-10">
            <i class="fas fa-car text-9xl"></i>
        </div>
        <h1 class="text-4xl font-bold mb-2 flex items-center gap-3 relative z-10">
            <i class="fas fa-route text-5xl"></i>
            Transportation Service
        </h1>
        <p class="text-orange-100 dark:text-orange-200 text-lg relative z-10">Real-time tracking and management system</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Active Rides -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Active Rides</h3>
                <div class="relative">
                    <i class="fas fa-car-side text-3xl opacity-30"></i>
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-white rounded-full animate-ping"></span>
                </div>
            </div>
            <p class="text-3xl font-bold">{{ $stats['active_rides'] ?? 0 }}</p>
            <p class="text-sm opacity-75 mt-1">Currently on road</p>
        </div>

        <!-- Completed Today -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Completed</h3>
                <i class="fas fa-check-circle text-3xl opacity-30"></i>
            </div>
            <p class="text-3xl font-bold">{{ $stats['completed_today'] ?? 0 }}</p>
            <p class="text-sm opacity-75 mt-1">Today's trips</p>
        </div>

        <!-- Pending Bookings -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 dark:from-yellow-600 dark:to-yellow-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Pending</h3>
                <i class="fas fa-clock text-3xl opacity-30"></i>
            </div>
            <p class="text-3xl font-bold">{{ $stats['pending'] ?? 0 }}</p>
            <p class="text-sm opacity-75 mt-1">Awaiting pickup</p>
        </div>

        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Revenue</h3>
                <i class="fas fa-dollar-sign text-3xl opacity-30"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($stats['revenue'] ?? 0, 2) }}</p>
            <p class="text-sm opacity-75 mt-1">Today's earnings</p>
        </div>
    </div>

    <!-- Live Map Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
            <i class="fas fa-map-marked-alt text-[#F37021]"></i>
            Live Tracking
        </h2>
        <div class="relative bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-900 dark:to-gray-800 rounded-xl h-96 overflow-hidden border-2 border-blue-200 dark:border-gray-700">
            <!-- Map Grid -->
            <div class="absolute inset-0 grid grid-cols-10 grid-rows-10 opacity-10">
                @for($i = 0; $i < 100; $i++)
                    <div class="border border-gray-400 dark:border-gray-600"></div>
                @endfor
            </div>

            <!-- Animated Roads -->
            <svg class="absolute inset-0 w-full h-full">
                <line x1="0" y1="50%" x2="100%" y2="50%" stroke="#94a3b8" stroke-width="4" class="dark:opacity-50"/>
                <line x1="50%" y1="0" x2="50%" y2="100%" stroke="#94a3b8" stroke-width="4" class="dark:opacity-50"/>
                <line x1="0" y1="0" x2="100%" y2="100%" stroke="#cbd5e1" stroke-width="2" stroke-dasharray="10,10" class="dark:opacity-30"/>
            </svg>

            <!-- Location Markers -->
            <div class="absolute top-1/4 left-1/4 animate-bounce">
                <div class="relative">
                    <i class="fas fa-map-marker-alt text-4xl text-green-500"></i>
                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-green-500 text-white px-2 py-1 rounded text-xs whitespace-nowrap">Cairo</span>
                </div>
            </div>

            <div class="absolute top-3/4 right-1/4 animate-bounce">
                <div class="relative">
                    <i class="fas fa-map-marker-alt text-4xl text-red-500"></i>
                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-red-500 text-white px-2 py-1 rounded text-xs whitespace-nowrap">Alexandria</span>
                </div>
            </div>

            <!-- Animated Cars -->
            <div id="car1" class="absolute top-1/4 left-1/4 transition-all duration-[3000ms] ease-linear">
                <div class="relative animate-pulse">
                    <i class="fas fa-car text-3xl text-[#F37021]"></i>
                    <div class="absolute -top-2 -right-2 w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                </div>
            </div>

            <div id="car2" class="absolute top-1/2 left-1/3 transition-all duration-[4000ms] ease-linear">
                <div class="relative animate-pulse">
                    <i class="fas fa-truck text-3xl text-blue-600"></i>
                    <div class="absolute -top-2 -right-2 w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                </div>
            </div>

            <div id="car3" class="absolute top-2/3 right-1/3 transition-all duration-[3500ms] ease-linear">
                <div class="relative animate-pulse">
                    <i class="fas fa-shuttle-van text-3xl text-purple-600"></i>
                    <div class="absolute -top-2 -right-2 w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
            <i class="fas fa-filter text-[#F37021]"></i>
            Filter Rides
        </h2>
        <form method="GET" action="{{ route('dashboard.transport') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select name="status" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#F37021] transition">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vehicle Type</label>
                <select name="vehicle_type" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#F37021] transition">
                    <option value="">All Vehicles</option>
                    <option value="car" {{ request('vehicle_type') == 'car' ? 'selected' : '' }}>Car</option>
                    <option value="van" {{ request('vehicle_type') == 'van' ? 'selected' : '' }}>Van</option>
                    <option value="truck" {{ request('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                    <option value="bus" {{ request('vehicle_type') == 'bus' ? 'selected' : '' }}>Bus</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                <input type="date" name="date" value="{{ request('date') }}" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#F37021] transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                <div class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer name..." class="flex-1 border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#F37021] transition">
                    <button type="submit" class="bg-[#F37021] hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Transportation Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="bg-gradient-to-r from-[#F37021] to-orange-600 dark:from-[#d85e05] dark:to-orange-700 text-white p-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i class="fas fa-list"></i>
                Transport Bookings
            </h2>
            <button onclick="location.reload()" class="bg-white/20 hover:bg-white/30 dark:bg-white/10 dark:hover:bg-white/20 px-4 py-2 rounded-lg transition flex items-center gap-2">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-900 border-b-2 border-gray-300 dark:border-gray-600">
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">ID</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">Customer</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">Phone</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">Route</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">Vehicle</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">Date & Time</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">Price</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700 dark:text-gray-300">Status</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transports ?? [] as $transport)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-orange-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">
                                <div class="flex flex-col">
                                    <span>#TR-{{ str_pad($transport->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    @if($transport->booking_id)
                                        <span class="text-xs text-orange-600 dark:text-orange-400">
                                            <i class="fas fa-link"></i> From Booking
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800 dark:text-white">{{ $transport->customer_name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $transport->customer_email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <a href="tel:{{ $transport->phone }}" class="flex items-center gap-1 hover:text-green-600 transition">
                                    <i class="fas fa-phone text-green-500"></i> {{ $transport->phone }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-1 text-green-600 dark:text-green-400 font-semibold">
                                            <i class="fas fa-map-marker-alt text-xs"></i>
                                            {{ $transport->pickup_location }}
                                        </div>
                                        @if($transport->distance)
                                            <div class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500 my-1">
                                                <i class="fas fa-arrows-alt-v"></i>
                                                {{ $transport->distance }}
                                            </div>
                                        @endif
                                        <div class="flex items-center gap-1 text-red-600 dark:text-red-400 font-semibold">
                                            <i class="fas fa-flag-checkered text-xs"></i>
                                            {{ $transport->drop_location }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $vehicleIcons = [
                                        'car' => 'fa-car',
                                        'van' => 'fa-shuttle-van',
                                        'truck' => 'fa-truck',
                                        'bus' => 'fa-bus',
                                    ];
                                    $vehicleColors = [
                                        'car' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'van' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                        'truck' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'bus' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    ];
                                @endphp
                                <span class="{{ $vehicleColors[$transport->vehicle_type] ?? 'bg-gray-100' }} px-3 py-1 rounded-full text-sm font-bold inline-flex items-center gap-1">
                                    <i class="fas {{ $vehicleIcons[$transport->vehicle_type] ?? 'fa-car' }}"></i>
                                    {{ ucfirst($transport->vehicle_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($transport->pickup_date)->format('M d, Y') }}</span>
                                    <span class="text-sm"><i class="fas fa-clock"></i> {{ $transport->pickup_time }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-green-600 dark:text-green-400">
                                ${{ number_format($transport->price, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                    ];
                                    $statusIcons = [
                                        'active' => 'fa-car-side',
                                        'completed' => 'fa-check-circle',
                                        'pending' => 'fa-clock',
                                        'cancelled' => 'fa-times-circle',
                                    ];
                                @endphp
                                <span class="{{ $statusColors[$transport->status] ?? 'bg-gray-100' }} px-4 py-2 rounded-lg font-bold inline-flex items-center gap-1">
                                    <i class="fas {{ $statusIcons[$transport->status] ?? 'fa-circle' }}"></i>
                                    {{ ucfirst($transport->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('dashboard.transport.show', $transport->id) }}"
                                       class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white p-2 rounded-lg transition"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($transport->status == 'pending')
                                        <button onclick="startTrip({{ $transport->id }})"
                                                class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white p-2 rounded-lg transition"
                                                title="Start Trip">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    @endif

                                    @if($transport->status == 'active')
                                        <button onclick="completeTrip({{ $transport->id }})"
                                                class="bg-purple-500 hover:bg-purple-600 dark:bg-purple-600 dark:hover:bg-purple-700 text-white p-2 rounded-lg transition"
                                                title="Complete Trip">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif

                                    <a href="tel:{{ $transport->phone }}"
                                       class="bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white p-2 rounded-lg transition"
                                       title="Call Customer">
                                        <i class="fas fa-phone"></i>
                                    </a>

                                    @if($transport->status != 'completed' && $transport->status != 'cancelled')
                                        <button onclick="cancelTrip({{ $transport->id }})"
                                                class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white p-2 rounded-lg transition"
                                                title="Cancel Trip">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox text-6xl mb-4 opacity-50"></i>
                                    <p class="text-xl font-semibold mb-2">No transport bookings found</p>
                                    <p class="text-sm">Transport bookings will appear here when created</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($transports) && $transports->hasPages())
            <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $transports->links() }}
            </div>
        @endif
    </div>

    <!-- Driver Performance & Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Drivers -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-trophy text-yellow-500"></i>
                Top Drivers Today
            </h3>
            <div class="space-y-4">
                @php
                    $drivers = [
                        ['name' => 'Ahmed Ali', 'trips' => 12, 'rating' => 4.9, 'earning' => 250],
                        ['name' => 'Mohamed Saad', 'trips' => 10, 'rating' => 4.8, 'earning' => 220],
                        ['name' => 'Omar Hassan', 'trips' => 9, 'rating' => 4.7, 'earning' => 195],
                        ['name' => 'Mahmoud Youssef', 'trips' => 8, 'rating' => 4.6, 'earning' => 180],
                    ];
                @endphp
                @foreach($drivers as $index => $driver)
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-gray-900 dark:to-gray-800 rounded-lg border border-orange-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#F37021] dark:bg-[#ff8c42] text-white rounded-full flex items-center justify-center font-bold text-lg">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 dark:text-white">{{ $driver['name'] }}</p>
                                <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                    <span><i class="fas fa-route text-blue-500"></i> {{ $driver['trips'] }} trips</span>
                                    <span><i class="fas fa-star text-yellow-500"></i> {{ $driver['rating'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600 dark:text-green-400">${{ $driver['earning'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Today</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-bell text-[#F37021]"></i>
                Recent Activities
            </h3>
            <div class="space-y-4">
                @php
                    $activities = [
                        ['type' => 'completed', 'text' => 'Trip completed successfully', 'time' => '2 mins ago', 'icon' => 'fa-check-circle', 'color' => 'green'],
                        ['type' => 'started', 'text' => 'Driver started trip to Alexandria', 'time' => '5 mins ago', 'icon' => 'fa-car-side', 'color' => 'blue'],
                        ['type' => 'booking', 'text' => 'New booking received', 'time' => '10 mins ago', 'icon' => 'fa-calendar-plus', 'color' => 'orange'],
                        ['type' => 'payment', 'text' => 'Payment received: $45.00', 'time' => '15 mins ago', 'icon' => 'fa-dollar-sign', 'color' => 'purple'],
                        ['type' => 'cancelled', 'text' => 'Trip cancelled by customer', 'time' => '20 mins ago', 'icon' => 'fa-times-circle', 'color' => 'red'],
                    ];
                @endphp
                @foreach($activities as $activity)
                    <div class="flex items-start gap-3 p-3 bg-{{ $activity['color'] }}-50 dark:bg-{{ $activity['color'] }}-900/20 rounded-lg border border-{{ $activity['color'] }}-100 dark:border-{{ $activity['color'] }}-800">
                        <div class="bg-{{ $activity['color'] }}-500 dark:bg-{{ $activity['color'] }}-600 text-white p-2 rounded-full flex-shrink-0">
                            <i class="fas {{ $activity['icon'] }}"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 dark:text-white">{{ $activity['text'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-clock"></i> {{ $activity['time'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('dashboard.transport.create') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 text-white p-6 rounded-2xl shadow-xl hover:scale-105 transition-transform flex items-center gap-4">
            <i class="fas fa-plus-circle text-4xl"></i>
            <div class="text-left">
                <h4 class="font-bold text-lg">New Booking</h4>
                <p class="text-sm opacity-80">Create ride request</p>
            </div>
        </a>

        <button onclick="alert('Add Driver feature coming soon!')" class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 text-white p-6 rounded-2xl shadow-xl hover:scale-105 transition-transform flex items-center gap-4">
            <i class="fas fa-user-plus text-4xl"></i>
            <div class="text-left">
                <h4 class="font-bold text-lg">Add Driver</h4>
                <p class="text-sm opacity-80">Register new driver</p>
            </div>
        </button>

        <button onclick="alert('Reports feature coming soon!')" class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 text-white p-6 rounded-2xl shadow-xl hover:scale-105 transition-transform flex items-center gap-4">
            <i class="fas fa-chart-bar text-4xl"></i>
            <div class="text-left">
                <h4 class="font-bold text-lg">Reports</h4>
                <p class="text-sm opacity-80">View analytics</p>
            </div>
        </button>

        <button onclick="alert('Settings feature coming soon!')" class="bg-gradient-to-br from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-700 text-white p-6 rounded-2xl shadow-xl hover:scale-105 transition-transform flex items-center gap-4">
            <i class="fas fa-cog text-4xl"></i>
            <div class="text-left">
                <h4 class="font-bold text-lg">Settings</h4>
                <p class="text-sm opacity-80">Configure system</p>
            </div>
        </button>
    </div>
</div>

<!-- Notification Container -->
<div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
// ==================== CSRF Token ====================
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// ==================== Notification System ====================
function showNotification(message, type = 'success', duration = 3000) {
    const colors = {
        'success': 'bg-green-500',
        'error': 'bg-red-500',
        'warning': 'bg-yellow-500',
        'info': 'bg-blue-500'
    };

    const icons = {
        'success': 'fa-check-circle',
        'error': 'fa-times-circle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    };

    const notification = document.createElement('div');
    notification.className = `${colors[type]} text-white px-6 py-4 rounded-lg shadow-xl animate-slideIn flex items-center gap-3 min-w-[300px]`;
    notification.innerHTML = `
        <i class="fas ${icons[type]} text-2xl"></i>
        <div class="flex-1">
            <p class="font-semibold">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    `;

    const container = document.getElementById('notificationContainer');
    container.appendChild(notification);

    setTimeout(() => {
        notification.style.transition = 'all 0.3s';
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, duration);
}

// ==================== Car Animation ====================
function animateCars() {
    const cars = [
        { id: 'car1', path: [
            { top: '25%', left: '25%' },
            { top: '35%', left: '45%' },
            { top: '50%', left: '60%' },
            { top: '65%', left: '75%' },
            { top: '50%', left: '50%' },
            { top: '25%', left: '25%' }
        ]},
        { id: 'car2', path: [
            { top: '50%', left: '33%' },
            { top: '40%', left: '50%' },
            { top: '30%', left: '70%' },
            { top: '45%', left: '55%' },
            { top: '50%', left: '33%' }
        ]},
        { id: 'car3', path: [
            { top: '66%', left: '66%' },
            { top: '55%', left: '50%' },
            { top: '40%', left: '35%' },
            { top: '50%', left: '45%' },
            { top: '66%', left: '66%' }
        ]}
    ];

    cars.forEach((car, index) => {
        let currentPoint = 0;
        const carElement = document.getElementById(car.id);

        setInterval(() => {
            currentPoint = (currentPoint + 1) % car.path.length;
            if (carElement) {
                carElement.style.top = car.path[currentPoint].top;
                carElement.style.left = car.path[currentPoint].left;
            }
        }, 3000 + (index * 500));
    });
}

// ==================== Transport Actions ====================
async function startTrip(transportId) {
    if (!confirm('Are you sure you want to start this trip?')) {
        return;
    }

    try {
        const response = await fetch(`/dashboard/transport/${transportId}/start`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            showNotification('Trip started successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Failed to start trip', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred while starting the trip', 'error');
    }
}

async function completeTrip(transportId) {
    if (!confirm('Mark this trip as completed?')) {
        return;
    }

    try {
        const response = await fetch(`/dashboard/transport/${transportId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            showNotification('Trip completed successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Failed to complete trip', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred while completing the trip', 'error');
    }
}

async function cancelTrip(transportId) {
    const reason = prompt('Please provide a reason for cancellation:');

    if (!reason) {
        return;
    }

    try {
        const response = await fetch(`/dashboard/transport/${transportId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ reason: reason })
        });

        const data = await response.json();

        if (data.success) {
            showNotification('Trip cancelled successfully', 'warning');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Failed to cancel trip', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred while cancelling the trip', 'error');
    }
}

// ==================== Initialize ====================
document.addEventListener('DOMContentLoaded', () => {
    // Start car animation
    animateCars();

    // Animate stats cards on load
    const statsCards = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4 > div');
    statsCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Check for success/error messages from Laravel session
    @if(session('success'))
        showNotification("{{ session('success') }}", 'success');
    @endif

    @if(session('error'))
        showNotification("{{ session('error') }}", 'error');
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            showNotification("{{ $error }}", 'error');
        @endforeach
    @endif
});

// Auto-refresh stats every 30 seconds
setInterval(() => {
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(response => {
        if (response.ok) {
            console.log('Stats refreshed');
        }
    }).catch(error => {
        console.error('Auto-refresh failed:', error);
    });
}, 30000);
</script>

<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-slideIn {
    animation: slideIn 0.3s ease-out;
}

/* Dark mode hover effects */
.dark .hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Smooth transitions for all interactive elements */
button, a {
    transition: all 0.3s ease;
}

/* Custom scrollbar for dark mode */
.dark ::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.dark ::-webkit-scrollbar-track {
    background: #1f2937;
}

.dark ::-webkit-scrollbar-thumb {
    background: #4b5563;
    border-radius: 4px;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}
</style>

@endsection
