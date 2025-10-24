@extends('layouts.dashboard')

@section('title', 'Hotel Manager')
@section('header', 'Hotel Manager')

@section('content')

    <!-- Navigation Tabs -->
    <nav
        class="flex flex-wrap gap-3 mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
        <a href="{{ route('dashboard.hotel') }}"
            class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition bg-[#F46A06] text-white font-semibold">
            <i class="fas fa-hotel w-4 h-4"></i> Hotel
        </a>
        <a href="{{ route('dashboard.restaurant') }}"
            class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20 font-semibold">
            <i class="fas fa-utensils w-4 h-4"></i> Restaurant
        </a>
        <a href="{{ route('dashboard.coffee') }}"
            class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20 font-semibold">
            <i class="fas fa-mug-hot w-4 h-4"></i> Coffee Shop
        </a>
        <a href="{{ route('dashboard.payment') }}"
            class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20 font-semibold">
            <i class="fas fa-credit-card w-4 h-4"></i> Payment
        </a>
        <a href="{{ route('dashboard.transport') }}"
            class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20 font-semibold">
            <i class="fas fa-car w-4 h-4"></i> Transportation
        </a>
    </nav>

    <!-- Add Hotel Form -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
            <span class="text-2xl">🏨</span> Add New Hotel
        </h2>

        <form action="{{ route('dashboard.hotel.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6"
            id="hotelForm">
            @csrf

            <!-- Basic Information -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-[#F46A06]"></i> Basic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hotel Name *</label>
                        <input type="text" name="name" required
                            class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition"
                            value="{{ old('name') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City *</label>
                        <input type="text" name="city" required
                            class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition"
                            value="{{ old('city') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                        <input type="text" name="address"
                            class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition"
                            value="{{ old('address') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Rooms</label>
                        <input type="number" name="capacity" min="0"
                            class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition"
                            value="{{ old('capacity') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Manager</label>
                        <input type="text" name="manager"
                            class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition"
                            value="{{ old('manager') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact</label>
                        <input type="tel" name="contact"
                            class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition"
                            value="{{ old('contact') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" name="email"
                            class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition"
                            value="{{ old('email') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label>
                        <input type="url" name="website"
                            class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition"
                            value="{{ old('website') }}">
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-file-alt text-[#F46A06]"></i> Description
                </h3>
                <textarea name="description" rows="4"
                    class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition resize-none"
                    placeholder="Describe your hotel...">{{ old('description') }}</textarea>
            </div>

            <!-- Services -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-concierge-bell text-[#F46A06]"></i> Services
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach(['Free Wi-Fi', 'Swimming Pool', 'Restaurant', 'Parking', 'Gym', 'Airport Shuttle', 'Spa', 'Room Service', 'Free car parking', 'Fitness center', 'Meetings', 'Yoga class for adults'] as $service)
                        <label
                            class="flex items-center gap-2 p-3 border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-[#F46A06]/5 dark:hover:bg-[#F46A06]/20 transition">
                            <input type="checkbox" name="services[]" value="{{ $service }}"
                                class="rounded text-[#F46A06] focus:ring-[#F46A06] dark:bg-gray-600 dark:border-gray-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $service }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Images -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-images text-[#F46A06]"></i> Images
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Main Image</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#F46A06] file:text-white hover:file:bg-orange-600 file:cursor-pointer">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Max: 50MB | Formats: JPG, PNG, WEBP, GIF
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gallery
                            (Multiple)</label>
                        <input type="file" name="gallery[]" multiple accept="image/*"
                            class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#F46A06] file:text-white hover:file:bg-orange-600 file:cursor-pointer">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Select multiple images for gallery</p>
                    </div>
                </div>
            </div>

            <!-- Rooms with Categories -->
            <div id="rooms-section">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex justify-between items-center">
                    <span class="flex items-center gap-2"><i class="fas fa-bed text-[#F46A06]"></i> Hotel Rooms</span>
                    <button type="button" onclick="addRoom()"
                        class="bg-[#F46A06] hover:bg-orange-600 dark:bg-[#ff8c42] dark:hover:bg-[#F46A06] text-white px-4 py-2 rounded-lg transition text-sm font-semibold flex items-center gap-2 shadow-lg">
                        <i class="fas fa-plus"></i> Add Room
                    </button>
                </h3>
                <div id="rooms-container" class="space-y-4">
                    <div
                        class="room-item border-2 border-[#F46A06]/30 dark:border-[#F46A06]/50 rounded-lg p-4 relative bg-gradient-to-br from-orange-50 to-white dark:from-gray-700 dark:to-gray-800">
                        <div
                            class="absolute -top-3 left-4 bg-[#F46A06] text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                            Room #1
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mt-2">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Room Name
                                    *</label>
                                <input type="text" name="rooms[0][name]" placeholder="e.g. Executive Suite" required
                                    class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                            </div>
                            <div class="mb-4">
                                <label
                                    class="block text-xs font-medium text-gray-700 mb-2 dark:text-gray-300">[translate:Category]
                                    *</label>
                                <select name="rooms[0][category]" required
                                    class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition-shadow duration-300 hover:shadow-lg">
                                    <option value="">[translate:Select Category]</option>
                                    <option value="VIP">VIP</option>
                                    <option value="Deluxe">Deluxe</option>
                                    <option value="Standard">Standard</option>
                                    <option value="Economy">Economy</option>
                                    <option value="Suite">Suite</option>
                                    <option value="Presidential">Presidential</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Price (USD)
                                    *</label>
                                <input type="number" name="rooms[0][price_usd]" placeholder="e.g. 100" required min="0"
                                    step="0.01"
                                    class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Available
                                    Units *</label>
                                <input type="number" name="rooms[0][availability]" placeholder="e.g. 5" required min="0"
                                    class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Room
                                    Image</label>
                                <input type="file" name="rooms[0][image]" accept="image/*"
                                    class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-[#F46A06] file:text-white hover:file:bg-orange-600 file:cursor-pointer">
                            </div>
                            <div class="lg:col-span-1">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Capacity
                                    (Guests)</label>
                                <input type="number" name="rooms[0][capacity]" placeholder="e.g. 2" min="1"
                                    class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                            </div>
                            <div class="md:col-span-2 lg:col-span-3">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Room
                                    Description</label>
                                <textarea name="rooms[0][description]"
                                    placeholder="Describe the room amenities, features, and benefits..." rows="2"
                                    class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-3 pt-4">
                <button type="reset"
                    class="px-6 py-2 border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition font-semibold">Reset</button>
                <button type="submit"
                    class="bg-[#F46A06] hover:bg-orange-600 dark:bg-[#ff8c42] dark:hover:bg-[#F46A06] text-white px-6 py-2 rounded-lg transition flex items-center gap-2 font-semibold shadow-lg">
                    <i class="fas fa-save"></i>
                    <span>Save Hotel</span>
                    <i class="fas fa-spinner fa-spin hidden" id="submitSpinner"></i>
                </button>
            </div>
        </form>

        <!-- Hotels List -->
        <hr class="my-8 border-2 border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
            <i class="fas fa-list text-[#F46A06]"></i>
            Hotels List
        </h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F46A06] dark:bg-[#d85e05] text-white">
                        <th class="px-4 py-3 rounded-tl-lg">#</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">City</th>
                        <th class="px-4 py-3">Total Rooms</th>
                        <th class="px-4 py-3">Manager</th>
                        <th class="px-4 py-3">Contact</th>
                        <th class="px-4 py-3 text-center rounded-tr-lg">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facilities as $index => $f)
                        <tr
                            class="border-b border-gray-200 dark:border-gray-700 hover:bg-orange-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-4 py-3 text-gray-800 dark:text-gray-200">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">{{ $f->name }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $f->city }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $f->capacity ?? $f->rooms->count() }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $f->manager ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $f->contact ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('dashboard.hotel.edit', $f->id) }}"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition font-medium inline-flex items-center gap-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('dashboard.hotel.destroy', $f->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this hotel?')"
                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition font-medium inline-flex items-center gap-1">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-8">
                                <i class="fas fa-inbox text-5xl mx-auto mb-2 opacity-30 block"></i>
                                <p class="font-medium">No hotels found</p>
                                <p class="text-sm">Add your first hotel using the form above!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Room Categories Summary -->
        @if($facilities->count() > 0)
            <div
                class="mt-8 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-gray-700 dark:to-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-[#F46A06]"></i> Room Categories Overview
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @php
                        $allRooms = $facilities->flatMap->rooms;
                        $categoryCounts = $allRooms->groupBy('category')->map->count();
                    @endphp
                    @foreach(['VIP', 'Presidential', 'Suite', 'Deluxe', 'Standard', 'Economy'] as $category)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg p-4 text-center shadow-sm border-2 border-orange-200 dark:border-gray-600 hover:scale-105 transition-transform">
                            <div class="text-2xl font-bold text-[#F46A06]">{{ $categoryCounts[$category] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $category }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Bookings Section -->
        @if(isset($bookings) && $bookings->count() > 0)
            <hr class="my-8 border-2 border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-[#F46A06]"></i>
                Recent Bookings
            </h3>
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-gradient-to-r from-[#F46A06] to-orange-600 dark:from-[#d85e05] dark:to-orange-700 text-white">
                            <th class="px-4 py-3 rounded-tl-lg">#</th>
                            <th class="px-4 py-3">Guest Name</th>
                            <th class="px-4 py-3">Room</th>
                            <th class="px-4 py-3">Check-in</th>
                            <th class="px-4 py-3">Check-out</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center rounded-tr-lg">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings->take(10) as $index => $booking)
                            <tr
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-orange-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-200">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">{{ $booking->guest_firstname }}
                                    {{ $booking->guest_lastname }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $booking->room->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($booking->checkin_date)->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($booking->checkout_date)->format('M d, Y') }}</td>
                                <td class="px-4 py-3 font-bold text-[#F46A06] dark:text-[#ff8c42]">
                                    {{ number_format($booking->total_price_rwf) }} RWF</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                        {{ ucfirst($booking->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button
                                        class="text-[#F46A06] dark:text-[#ff8c42] hover:text-orange-600 dark:hover:text-orange-500 transition font-medium inline-flex items-center gap-1">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    @if($facilities->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 text-white rounded-lg p-6 shadow-xl transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Total Hotels</p>
                        <h3 class="text-3xl font-bold">{{ $facilities->count() }}</h3>
                    </div>
                    <i class="fas fa-hotel text-4xl opacity-30"></i>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 text-white rounded-lg p-6 shadow-xl transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Total Rooms</p>
                        <h3 class="text-3xl font-bold">{{ $facilities->sum('capacity') }}</h3>
                    </div>
                    <i class="fas fa-bed text-4xl opacity-30"></i>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 text-white rounded-lg p-6 shadow-xl transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Active Bookings</p>
                        <h3 class="text-3xl font-bold">{{ isset($bookings) ? $bookings->count() : 0 }}</h3>
                    </div>
                    <i class="fas fa-calendar-check text-4xl opacity-30"></i>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-700 text-white rounded-lg p-6 shadow-xl transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Cities Covered</p>
                        <h3 class="text-3xl font-bold">{{ $facilities->unique('city')->count() }}</h3>
                    </div>
                    <i class="fas fa-map-marker-alt text-4xl opacity-30"></i>
                </div>
            </div>
        </div>
    @endif

    <script>
        let roomIndex = 1;

        function addRoom() {
            const container = document.getElementById('rooms-container');
            const isDark = document.documentElement.classList.contains('dark');

            const roomHtml = `
            <div class="room-item border-2 border-[#F46A06]/30 dark:border-[#F46A06]/50 rounded-lg p-4 relative bg-gradient-to-br from-orange-50 to-white dark:from-gray-700 dark:to-gray-800 animate-fadeIn">
                <div class="absolute -top-3 left-4 bg-[#F46A06] text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                    Room #${roomIndex + 1}
                </div>
                <button type="button" onclick="removeRoom(this)" class="absolute -top-3 right-4 bg-red-500 hover:bg-red-600 text-white w-7 h-7 rounded-full text-xs font-bold shadow-lg transition flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mt-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Room Name *</label>
                        <input type="text" name="rooms[${roomIndex}][name]" placeholder="e.g. Executive Suite" required class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                        <select name="rooms[${roomIndex}][category]" required class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                            <option value="">Select Category</option>
                            <option value="VIP">VIP</option>
                            <option value="Deluxe">Deluxe</option>
                            <option value="Standard">Standard</option>
                            <option value="Economy">Economy</option>
                            <option value="Suite">Suite</option>
                            <option value="Presidential">Presidential</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Price (USD) *</label>
                        <input type="number" name="rooms[${roomIndex}][price_usd]" placeholder="e.g. 100" required min="0" step="0.01" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Available Units *</label>
                        <input type="number" name="rooms[${roomIndex}][availability]" placeholder="e.g. 5" required min="0" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Room Image</label>
                        <input type="file" name="rooms[${roomIndex}][image]" accept="image/*" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-[#F46A06] file:text-white hover:file:bg-orange-600 file:cursor-pointer">
                    </div>
                    <div class="lg:col-span-1">
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Capacity (Guests)</label>
                        <input type="number" name="rooms[${roomIndex}][capacity]" placeholder="e.g. 2" min="1" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                    </div>
                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Room Description</label>
                        <textarea name="rooms[${roomIndex}][description]" placeholder="Describe the room amenities, features, and benefits..." rows="2" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]"></textarea>
                    </div>
                </div>
            </div>
        `;

            container.insertAdjacentHTML('beforeend', roomHtml);
            roomIndex++;
        }

        function removeRoom(button) {
            if (confirm('Are you sure you want to remove this room?')) {
                button.closest('.room-item').remove();
            }
        }

        // Form submission with loading state
        document.getElementById('hotelForm')?.addEventListener('submit', function () {
            const spinner = document.getElementById('submitSpinner');
            const submitText = this.querySelector('button[type="submit"] span');
            if (spinner && submitText) {
                spinner.classList.remove('hidden');
                submitText.textContent = 'Saving...';
            }
        });

        // Animate stats cards on load
        document.addEventListener('DOMContentLoaded', function () {
            const stats = document.querySelectorAll('.bg-gradient-to-br');
            stats.forEach((stat, index) => {
                setTimeout(() => {
                    stat.style.opacity = '0';
                    stat.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        stat.style.transition = 'all 0.5s ease';
                        stat.style.opacity = '1';
                        stat.style.transform = 'translateY(0)';
                    }, 10);
                }, index * 100);
            });
        });
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
    </style>

@endsection