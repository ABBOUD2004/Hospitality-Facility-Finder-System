@extends('layouts.dashboard')

@section('title', 'Coffee Shop Manager')
@section('header', 'Coffee Shop Manager')

@section('content')

<!-- Navigation Tabs -->
<nav class="flex flex-wrap gap-3 mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
    <a href="{{ route('dashboard.hotel') }}" class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20 font-semibold">
        <i class="fas fa-hotel w-4 h-4"></i> Hotel
    </a>
    <a href="{{ route('dashboard.restaurant') }}" class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20 font-semibold">
        <i class="fas fa-utensils w-4 h-4"></i> Restaurant
    </a>
    <a href="{{ route('dashboard.coffee') }}" class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition bg-[#F46A06] text-white font-semibold">
        <i class="fas fa-mug-hot w-4 h-4"></i> Coffee Shop
    </a>
    <a href="{{ route('dashboard.payment') }}" class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20 font-semibold">
        <i class="fas fa-credit-card w-4 h-4"></i> Payment
    </a>
    <a href="{{ route('dashboard.transport') }}" class="flex items-center gap-2 px-4 py-2 border-2 border-[#F46A06] rounded-lg transition text-[#F46A06] dark:text-[#ff8c42] hover:bg-[#F46A06]/10 dark:hover:bg-[#F46A06]/20 font-semibold">
        <i class="fas fa-car w-4 h-4"></i> Transportation
    </a>
</nav>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="bg-green-100 dark:bg-green-900/30 border-2 border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-6 flex items-center gap-2 animate-pulse">
        <i class="fas fa-check-circle text-lg"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 dark:bg-red-900/30 border-2 border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
        <i class="fas fa-exclamation-circle text-lg"></i>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
@endif

<!-- Add Coffee Shop Form -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 mb-8">
    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
        <span class="text-2xl">☕</span> Add New Coffee Shop
    </h2>

    <form action="{{ route('dashboard.coffee.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="coffeeForm">
        @csrf

        <!-- Basic Information -->
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-[#F46A06]"></i> Basic Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Coffee Shop Name *</label>
                    <input type="text" name="name" required class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition" value="{{ old('name') }}" placeholder="e.g., Starbucks Kigali">
                    @error('name')<span class="text-red-500 dark:text-red-400 text-xs block mt-1">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City *</label>
                    <input type="text" name="city" required class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition" value="{{ old('city') }}" placeholder="e.g., Kigali">
                    @error('city')<span class="text-red-500 dark:text-red-400 text-xs block mt-1">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seats Available</label>
                    <input type="number" name="capacity" min="0" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition" value="{{ old('capacity') }}" placeholder="e.g., 50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Owner Name *</label>
                    <input type="text" name="manager" required class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition" value="{{ old('manager') }}" placeholder="Owner/Manager name">
                    @error('manager')<span class="text-red-500 dark:text-red-400 text-xs block mt-1">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact Number *</label>
                    <input type="tel" name="contact" required class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition" value="{{ old('contact') }}" placeholder="+250 XXX XXX XXX">
                    @error('contact')<span class="text-red-500 dark:text-red-400 text-xs block mt-1">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-file-alt text-[#F46A06]"></i> Description
            </h3>
            <textarea name="description" rows="4" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition resize-none" placeholder="Describe your coffee shop, ambiance, specialties...">{{ old('description') }}</textarea>
        </div>

        <!-- Services -->
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-concierge-bell text-[#F46A06]"></i> Services We Offer
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @php
                    $services = [
                        ['name' => 'Outside catering', 'icon' => '🍱'],
                        ['name' => 'Birthday ceremonies', 'icon' => '🎂'],
                        ['name' => 'Smoking area', 'icon' => '🚬'],
                        ['name' => 'Free wifi', 'icon' => '📶'],
                        ['name' => 'Meetings', 'icon' => '💼'],
                        ['name' => 'Engagement events', 'icon' => '💍'],
                        ['name' => 'Parking', 'icon' => '🅿️'],
                        ['name' => 'Live Music', 'icon' => '🎵'],
                        ['name' => 'Outdoor Seating', 'icon' => '🌳'],
                    ];
                @endphp
                @foreach($services as $service)
                    <label class="flex items-center gap-2 p-3 border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-[#F46A06]/5 dark:hover:bg-[#F46A06]/20 transition">
                        <input type="checkbox" name="services[]" value="{{ $service['name'] }}" class="rounded text-[#F46A06] focus:ring-[#F46A06] dark:bg-gray-600 dark:border-gray-500" {{ in_array($service['name'], old('services', [])) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $service['icon'] }} {{ $service['name'] }}</span>
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
                    <input type="file" name="image" accept="image/*" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#F46A06] file:text-white hover:file:bg-orange-600 file:cursor-pointer">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Max: 50MB | Formats: JPG, PNG, WEBP, GIF</p>
                    @error('image')<span class="text-red-500 dark:text-red-400 text-xs block mt-1">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gallery Images (Multiple)</label>
                    <input type="file" name="gallery[]" multiple accept="image/*" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#F46A06] file:text-white hover:file:bg-orange-600 file:cursor-pointer">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Select multiple images for your gallery</p>
                </div>
            </div>
        </div>

        <!-- Menu Items -->
        <div id="menu-section">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex justify-between items-center">
                <span class="flex items-center gap-2">
                    <i class="fas fa-utensils text-[#F46A06]"></i> Menu Items
                </span>
                <button type="button" onclick="addMenuItem()" class="bg-[#F46A06] hover:bg-orange-600 dark:bg-[#ff8c42] dark:hover:bg-[#F46A06] text-white px-4 py-2 rounded-lg transition text-sm font-semibold flex items-center gap-2 shadow-lg">
                    <i class="fas fa-plus"></i> Add Menu Item
                </button>
            </h3>

            <div id="menu-items-container" class="space-y-4">
                <div class="menu-item border-2 border-[#F46A06]/30 dark:border-[#F46A06]/50 rounded-lg p-4 relative bg-gradient-to-br from-orange-50 to-white dark:from-gray-700 dark:to-gray-800">
                    <div class="absolute -top-3 left-4 bg-[#F46A06] text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                        Item #1
                    </div>
                    <div class="grid grid-cols-1 gap-3 mt-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Item Name *</label>
                                <input type="text" name="menu_items[0][name]" placeholder="e.g., Cappuccino" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                                <select name="menu_items[0][category]" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                                    <option value="Coffee drinks">☕ Coffee drinks</option>
                                    <option value="Main dishes">🍽️ Main dishes</option>
                                    <option value="Snacks">🍪 Snacks</option>
                                    <option value="Soft drinks">🥤 Soft drinks</option>
                                    <option value="Alcoholic drinks">🍺 Alcoholic drinks</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Price (RWF) *</label>
                                <input type="number" name="menu_items[0][price]" placeholder="5000" min="0" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Description (Optional)</label>
                            <textarea name="menu_items[0][description]" rows="2" placeholder="Brief description of the item..." class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Item Image</label>
                            <input type="file" name="menu_items[0][image]" accept="image/*" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-[#F46A06] file:text-white hover:file:bg-orange-600 file:cursor-pointer">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Upload an image of this menu item</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button type="reset" class="px-6 py-2 border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition font-semibold flex items-center gap-2">
                <i class="fas fa-redo"></i> Reset Form
            </button>
            <button type="submit" class="bg-[#F46A06] hover:bg-orange-600 dark:bg-[#ff8c42] dark:hover:bg-[#F46A06] text-white px-6 py-2 rounded-lg transition flex items-center gap-2 font-semibold shadow-lg">
                <i class="fas fa-save"></i>
                <span>Save Coffee Shop</span>
                <i class="fas fa-spinner fa-spin hidden" id="submitSpinner"></i>
            </button>
        </div>
    </form>
</div>

<!-- Coffee Shops List -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center justify-between">
        <span class="flex items-center gap-2">
            <i class="fas fa-list text-[#F46A06]"></i> Coffee Shops List ({{ $facilities->count() }})
        </span>
    </h3>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#F46A06] dark:bg-[#d85e05] text-white">
                    <th class="px-4 py-3 rounded-tl-lg">#</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">City</th>
                    <th class="px-4 py-3">Seats</th>
                    <th class="px-4 py-3">Owner</th>
                    <th class="px-4 py-3">Menu Items</th>
                    <th class="px-4 py-3 text-center rounded-tr-lg">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($facilities as $index => $f)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-orange-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200 font-medium">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($f->image)
                                    <img src="{{ asset($f->image) }}" alt="{{ $f->name }}" class="w-12 h-12 rounded-lg object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-orange-200 to-orange-300 dark:from-orange-700 dark:to-orange-800 rounded-lg flex items-center justify-center text-xl shadow-sm">☕</div>
                                @endif
                                <span class="font-semibold text-gray-800 dark:text-white">{{ $f->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $f->city }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $f->capacity ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $f->manager }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $f->menuItems->count() }} items
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('coffee.show', $f->id) }}" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white p-2 rounded-lg transition shadow-sm" title="View Details" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button onclick="openEditModal({{ $f->id }})" class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white p-2 rounded-lg transition shadow-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('dashboard.coffee.destroy', $f->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this coffee shop?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white p-2 rounded-lg transition shadow-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-8">
                            <i class="fas fa-mug-hot text-5xl mx-auto mb-2 opacity-30 block"></i>
                            <p class="font-medium">No coffee shops found</p>
                            <p class="text-sm">Add your first coffee shop using the form above!</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Stats Cards -->
@if($facilities->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
    <div class="bg-gradient-to-br from-amber-500 to-amber-600 dark:from-amber-600 dark:to-amber-700 text-white rounded-lg p-6 shadow-xl transform hover:scale-105 transition-transform">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 mb-1">Total Shops</p>
                <h3 class="text-3xl font-bold">{{ $facilities->count() }}</h3>
            </div>
            <i class="fas fa-store text-4xl opacity-30"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 text-white rounded-lg p-6 shadow-xl transform hover:scale-105 transition-transform">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 mb-1">Total Seats</p>
                <h3 class="text-3xl font-bold">{{ $facilities->sum('capacity') }}</h3>
            </div>
            <i class="fas fa-chair text-4xl opacity-30"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 text-white rounded-lg p-6 shadow-xl transform hover:scale-105 transition-transform">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 mb-1">Menu Items</p>
                <h3 class="text-3xl font-bold">{{ $facilities->sum(function($f) { return $f->menuItems->count(); }) }}</h3>
            </div>
            <i class="fas fa-utensils text-4xl opacity-30"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 text-white rounded-lg p-6 shadow-xl transform hover:scale-105 transition-transform">
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

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 hidden z-[9999] overflow-y-auto">
    <!-- Background Overlay -->
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" onclick="closeEditModal()"></div>

    <!-- Modal Content - CENTERED WITH SCROLL -->
    <div class="relative min-h-screen flex items-center justify-center p-4 py-12">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-4xl w-full my-8 overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#F46A06] via-orange-500 to-[#d85e05] p-6 flex items-center justify-between shadow-lg flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-md p-3 rounded-xl">
                        <i class="fas fa-edit text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white">Edit Coffee Shop</h3>
                        <p class="text-white/80 text-sm">Update coffee shop information</p>
                    </div>
                </div>
                <button onclick="closeEditModal()" type="button" class="bg-white/20 hover:bg-white/30 text-white w-10 h-10 rounded-xl flex items-center justify-center transition hover:scale-110">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Form - Scrollable Content -->
            <form id="editForm" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto">
                @csrf
                @method('PUT')

                <!-- Form Content -->
                <div id="editFormContent" class="p-6 space-y-6">
                    <div class="flex items-center justify-center py-12">
                        <i class="fas fa-spinner fa-spin text-4xl text-[#F46A06]"></i>
                    </div>
                </div>

                <!-- Buttons - Sticky Footer -->
                <div class="sticky bottom-0 bg-white dark:bg-gray-800 flex justify-end gap-3 p-6 border-t-2 border-gray-200 dark:border-gray-700 flex-shrink-0">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition font-semibold flex items-center gap-2">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="bg-gradient-to-r from-[#F46A06] to-orange-600 hover:from-orange-600 hover:to-[#F46A06] text-white px-8 py-3 rounded-xl transition flex items-center gap-2 font-semibold shadow-lg hover:shadow-xl hover:scale-105">
                        <i class="fas fa-save"></i>
                        <span>Update Coffee Shop</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(id) {
    // سكرول الصفحة لفوق أولاً
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });

    // انتظار صغير عشان السكرول يخلص، بعدين فتح المودال
    setTimeout(() => {
        const modal = document.getElementById('editModal');
        document.body.style.overflow = 'hidden'; // منع scroll الصفحة
        modal.classList.remove('hidden');
        modal.scrollTop = 0; // رجوع المودال لفوق
    }, 300); // 300ms للسكرول السموث
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = ''; // السماح بـ scroll الصفحة
}
</script>

<style>
#editModal {
    z-index: 9999 !important;
}

body.modal-open {
    overflow: hidden !important;
}
</style>
<script>
let menuItemIndex = 1;

// Facilities data from backend
const facilitiesData = @json($facilities);

function addMenuItem() {
    const container = document.getElementById('menu-items-container');
    const newItem = `
        <div class="menu-item border-2 border-[#F46A06]/30 dark:border-[#F46A06]/50 rounded-lg p-4 relative bg-gradient-to-br from-orange-50 to-white dark:from-gray-700 dark:to-gray-800 animate-fadeIn">
            <div class="absolute -top-3 left-4 bg-[#F46A06] text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                Item #${menuItemIndex + 1}
            </div>
            <button type="button" onclick="removeMenuItem(this)" class="absolute -top-3 right-4 bg-red-500 hover:bg-red-600 text-white w-7 h-7 rounded-full text-xs font-bold shadow-lg transition flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
            <div class="grid grid-cols-1 gap-3 mt-2">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Item Name *</label>
                        <input type="text" name="menu_items[${menuItemIndex}][name]" placeholder="e.g., Cappuccino" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                        <select name="menu_items[${menuItemIndex}][category]" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                            <option value="Coffee drinks">☕ Coffee drinks</option>
                            <option value="Main dishes">🍽️ Main dishes</option>
                            <option value="Snacks">🍪 Snacks</option>
                            <option value="Soft drinks">🥤 Soft drinks</option>
                            <option value="Alcoholic drinks">🍺 Alcoholic drinks</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Price (RWF) *</label>
                        <input type="number" name="menu_items[${menuItemIndex}][price]" placeholder="5000" min="0" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Description (Optional)</label>
                    <textarea name="menu_items[${menuItemIndex}][description]" rows="2" placeholder="Brief description of the item..." class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42]"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Item Image</label>
                    <input type="file" name="menu_items[${menuItemIndex}][image]" accept="image/*" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-[#F46A06] file:text-white hover:file:bg-orange-600 file:cursor-pointer">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Upload an image of this menu item</p>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newItem);
    menuItemIndex++;
}

function removeMenuItem(button) {
    if(confirm('Are you sure you want to remove this menu item?')) {
        button.closest('.menu-item').remove();
    }
}

// Open Edit Modal - FIXED
function openEditModal(facilityId) {
    console.log('Opening modal for facility:', facilityId);

    const facility = facilitiesData.find(f => f.id === facilityId);

    if (!facility) {
        alert('Facility not found!');
        console.error('Facility not found with ID:', facilityId);
        return;
    }

    console.log('Found facility:', facility);

    const modal = document.getElementById('editModal');
    if (!modal) {
        console.error('Modal element not found!');
        return;
    }

    // Show modal
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    const editForm = document.getElementById('editForm');
    editForm.action = `/dashboard/coffee/${facilityId}`;

    const formContent = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-store text-[#F46A06]"></i> Coffee Shop Name *
                </label>
                <input type="text" name="name" value="${facility.name || ''}" required class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#F46A06] transition">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-city text-[#F46A06]"></i> City *
                </label>
                <input type="text" name="city" value="${facility.city || ''}" required class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#F46A06] transition">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-users text-[#F46A06]"></i> Seats Available
                </label>
                <input type="number" name="capacity" value="${facility.capacity || ''}" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#F46A06] transition">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-user-tie text-[#F46A06]"></i> Owner Name *
                </label>
                <input type="text" name="manager" value="${facility.manager || ''}" required class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#F46A06] transition">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-phone text-[#F46A06]"></i> Contact Number *
                </label>
                <input type="tel" name="contact" value="${facility.contact || ''}" required class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#F46A06] transition">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-align-left text-[#F46A06]"></i> Description
                </label>
                <textarea name="description" rows="4" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#F46A06] transition resize-none">${facility.description || ''}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-image text-[#F46A06]"></i> Update Image
                </label>
                ${facility.image ? `
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Image:</p>
                        <img src="${facility.image}" alt="${facility.name}" class="w-32 h-32 object-cover rounded-xl border-2 border-gray-200 dark:border-gray-600 shadow-md">
                    </div>
                ` : ''}
                <input type="file" name="image" accept="image/*" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl px-4 py-3 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#F46A06] file:text-white hover:file:bg-orange-600 file:cursor-pointer transition">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle text-blue-500"></i> Leave empty to keep current image
                </p>
            </div>
        </div>
    `;

    document.getElementById('editFormContent').innerHTML = formContent;
    console.log('Modal opened successfully');
}

// Close Edit Modal - FIXED
function closeEditModal() {
    console.log('Closing modal');
    const modal = document.getElementById('editModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Form submission
document.getElementById('coffeeForm')?.addEventListener('submit', function() {
    const spinner = document.getElementById('submitSpinner');
    const submitText = this.querySelector('button[type="submit"] span');
    if(spinner && submitText) {
        spinner.classList.remove('hidden');
        submitText.textContent = 'Saving...';
    }
});

// Animate stats on load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, facilities data:', facilitiesData);

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

    // Auto-hide success messages
    const successAlert = document.querySelector('.animate-pulse');
    if(successAlert) {
        setTimeout(() => {
            successAlert.style.transition = 'all 0.5s ease';
            successAlert.style.opacity = '0';
            successAlert.style.transform = 'translateY(-20px)';
            setTimeout(() => successAlert.remove(), 500);
        }, 5000);
    }
});

// Close modal on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
    }
});

// Close modal on background click
document.getElementById('editModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
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
