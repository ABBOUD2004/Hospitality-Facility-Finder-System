@extends('layouts.dashboard')

@section('title', 'Bookings Management')
@section('header', 'Bookings Management')

@section('content')

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div
            class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 dark:text-blue-200 text-sm font-medium mb-1">Total Bookings</p>
                    <h3 class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 dark:bg-opacity-30 p-3 rounded-xl">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-yellow-500 to-yellow-600 dark:from-yellow-600 dark:to-yellow-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 dark:text-yellow-200 text-sm font-medium mb-1">Pending</p>
                    <h3 class="text-3xl font-bold">{{ $stats['pending'] ?? 0 }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 dark:bg-opacity-30 p-3 rounded-xl">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 dark:text-green-200 text-sm font-medium mb-1">Confirmed</p>
                    <h3 class="text-3xl font-bold">{{ $stats['confirmed'] ?? 0 }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 dark:bg-opacity-30 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 dark:text-purple-200 text-sm font-medium mb-1">Today's Revenue</p>
                    <h3 class="text-3xl font-bold">{{ number_format($stats['revenue'] ?? 0, 0) }}</h3>
                    <p class="text-purple-100 dark:text-purple-200 text-xs mt-1">RWF</p>
                </div>
                <div class="bg-white bg-opacity-20 dark:bg-opacity-30 p-3 rounded-xl">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Types Statistics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 border-l-4 border-purple-500 dark:border-purple-400 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Hotel Bookings</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['hotel'] ?? 0 }}</p>
                </div>
                <div class="text-purple-500 dark:text-purple-400 text-3xl">
                    <i class="fas fa-hotel"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 border-l-4 border-orange-500 dark:border-orange-400 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Restaurant Orders</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['restaurant'] ?? 0 }}</p>
                </div>
                <div class="text-orange-500 dark:text-orange-400 text-3xl">
                    <i class="fas fa-utensils"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 border-l-4 border-yellow-500 dark:border-yellow-400 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Coffee Orders</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['coffee'] ?? 0 }}</p>
                </div>
                <div class="text-yellow-500 dark:text-yellow-400 text-3xl">
                    <i class="fas fa-coffee"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 border-l-4 border-blue-500 dark:border-blue-400 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Transport</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['transport'] ?? 0 }}</p>
                </div>
                <div class="text-blue-500 dark:text-blue-400 text-3xl">
                    <i class="fas fa-car"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
            <i class="fas fa-filter text-[#F46A06]"></i> Filters & Search
        </h3>
        <form action="{{ route('dashboard.bookings.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Booking ID, Guest name, Phone..."
                        class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] focus:border-transparent transition">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>Checked In
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                    <select name="type"
                        class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition">
                        <option value="">All Types</option>
                        <option value="hotel" {{ request('type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                        <option value="restaurant" {{ request('type') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                        <option value="coffee" {{ request('type') == 'coffee' ? 'selected' : '' }}>Coffee Shop</option>
                        <option value="transport" {{ request('type') == 'transport' ? 'selected' : '' }}>Transport</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 bg-[#F46A06] hover:bg-orange-600 dark:bg-[#ff8c42] dark:hover:bg-[#F46A06] text-white px-4 py-2 rounded-lg transition font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('dashboard.bookings.index') }}"
                        class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg transition font-semibold">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 text-green-700 dark:text-green-300 p-4 mb-6 rounded-lg shadow-lg animate-pulse"
            role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 text-red-700 dark:text-red-300 p-4 mb-6 rounded-lg shadow-lg"
            role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Bookings Table -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-[#F46A06] to-orange-600 dark:from-[#d85e05] dark:to-orange-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Guest</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Details</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-orange-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">#{{ $booking->id }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->booking_reference }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $booking->full_guest_name }}
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">{{ $booking->guest_phone }}</div>
                                @if($booking->guest_email)
                                    <div class="text-xs text-gray-500 dark:text-gray-500">{{ $booking->guest_email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $typeColors = [
                                        'hotel' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                        'restaurant' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                        'coffee' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'coffee_shop' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'transport' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                    ];
                                    $typeIcons = [
                                        'hotel' => 'fa-hotel',
                                        'restaurant' => 'fa-utensils',
                                        'coffee' => 'fa-coffee',
                                        'coffee_shop' => 'fa-coffee',
                                        'transport' => 'fa-car',
                                    ];
                                @endphp
                                <span
                                    class="px-3 py-1 inline-flex items-center gap-2 text-xs leading-5 font-bold rounded-full {{ $typeColors[$booking->booking_type] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    <i class="fas {{ $typeIcons[$booking->booking_type] ?? 'fa-question' }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $booking->booking_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->booking_type == 'hotel')
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">
                                        {{ $booking->facility->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">{{ $booking->room->name ?? 'N/A' }} •
                                        {{ $booking->nights }} night(s)</div>
                                @elseif($booking->booking_type == 'transport')
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">
                                        {{ Str::limit($booking->pickup_location, 20) }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">→
                                        {{ Str::limit($booking->destination, 20) }}</div>
                                @elseif(in_array($booking->booking_type, ['restaurant', 'coffee', 'coffee_shop']))
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">
                                        {{ $booking->facility->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">
                                        @if($booking->order_items && is_array($booking->order_items))
                                            {{ count($booking->order_items) }} items ordered
                                        @else
                                            {{ $booking->number_of_guests }} guests
                                        @endif
                                    </div>
                                @else
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">
                                        {{ $booking->facility->name ?? 'N/A' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white font-medium">
                                    @if($booking->booking_type == 'hotel')
                                        {{ optional($booking->check_in)->format('M d, Y') ?? 'N/A' }}
                                    @else
                                        {{ optional($booking->reservation_date)->format('M d, Y') ?? optional($booking->created_at)->format('M d, Y') }}
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $booking->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-[#F46A06] dark:text-[#ff8c42]">
                                    {{ number_format($booking->total_price ?? $booking->total_price_rwf ?? 0, 0) }} RWF
                                </div>
                                @if($booking->payment_status)
                                    <div
                                        class="text-xs font-semibold {{ $booking->payment_status == 'paid' ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'checked_in' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'completed' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                    ];
                                @endphp
                                <span
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('dashboard.bookings.show', $booking) }}"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition p-1"
                                        title="View Details">
                                        <i class="fas fa-eye text-lg"></i>
                                    </a>

                                    <a href="{{ route('dashboard.bookings.invoice', $booking) }}"
                                        class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 transition p-1"
                                        title="View Invoice" target="_blank">
                                        <i class="fas fa-file-invoice text-lg"></i>
                                    </a>

                                    @if($booking->booking_type == 'transport' && $booking->status == 'pending')
                                        <button onclick="acceptTransportBooking({{ $booking->id }})"
                                            class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 transition p-1"
                                            title="Accept & Move to Transport">
                                            <i class="fas fa-check-circle text-lg"></i>
                                        </button>
                                    @endif

                                    @if($booking->status == 'pending' && $booking->booking_type != 'transport')
                                        <form action="{{ route('dashboard.bookings.update-status', $booking) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit"
                                                class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 transition p-1"
                                                title="Confirm">
                                                <i class="fas fa-check-circle text-lg"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($booking->status != 'cancelled' && $booking->status != 'completed')
                                        <button onclick="deleteBooking({{ $booking->id }})"
                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition p-1"
                                            title="Delete">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <p class="text-gray-500 dark:text-gray-400 text-lg font-semibold">No bookings found</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Try adjusting your filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
            <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal"
        class="hidden fixed inset-0 bg-black bg-opacity-60 dark:bg-opacity-80 flex items-center justify-center z-50 backdrop-blur-sm">
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md mx-4 shadow-2xl border border-gray-200 dark:border-gray-700 transform transition-all">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Delete Booking?</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this booking? This action
                cannot be undone.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 py-2 px-4 rounded-lg transition font-semibold">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white py-2 px-4 rounded-lg transition font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function deleteBooking(id) {
            const form = document.getElementById('deleteForm');
            form.action = `/dashboard/bookings/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });

        // Close modal on outside click
        document.getElementById('deleteModal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeDeleteModal();
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

            // Auto-hide success messages
            const successAlert = document.querySelector('.animate-pulse');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.transition = 'all 0.5s ease';
                    successAlert.style.opacity = '0';
                    successAlert.style.transform = 'translateY(-20px)';
                    setTimeout(() => successAlert.remove(), 500);
                }, 5000);
            }
        });
    </script>
{{-- Add this script at the end of the file --}}
@push('scripts')
<script>
async function acceptTransportBooking(bookingId) {
    if (!confirm('Accept this transport booking and move it to Transport Management?')) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
        const response = await fetch(`/dashboard/bookings/${bookingId}/accept-transport`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl z-50 animate-slideDown';
            successDiv.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                    <div>
                        <p class="font-bold">Success!</p>
                        <p class="text-sm">Booking moved to Transport</p>
                    </div>
                </div>
            `;
            document.body.appendChild(successDiv);

            // Reload after 1.5 seconds
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            alert(data.message || 'Failed to accept booking');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while accepting the booking');
    }
}
</script>
@endpush
@endsection
