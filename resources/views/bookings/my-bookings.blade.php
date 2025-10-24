@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">My Bookings</h1>
        <p class="text-gray-600">View and manage all your bookings and payments</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
            <p class="text-green-700 font-semibold">✓ {{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
            <p class="text-red-700 font-semibold">✗ {{ session('error') }}</p>
        </div>
    @endif

    <!-- Bookings List -->
    @forelse($bookings as $booking)
        <div class="bg-white rounded-lg shadow-lg mb-6 overflow-hidden hover:shadow-xl transition">
            <!-- Booking Header -->
            <div class="bg-gradient-to-r from-[#F37021] to-orange-600 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold mb-1">
                            {{ $booking->facility->name ?? 'N/A' }}
                        </h3>
                        <p class="text-orange-100">
                            Booking ID: #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                    <div class="text-right">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                            $paymentColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800',
                                'refunded' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <span class="{{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }} px-4 py-2 rounded-full text-sm font-bold">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Booking Type -->
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Booking Type</p>
                        <p class="font-semibold text-gray-800">
                            @if($booking->booking_type === 'hotel' || $booking->booking_type === 'room')
                                <i class="fas fa-bed"></i> Hotel Room
                            @elseif($booking->booking_type === 'restaurant')
                                <i class="fas fa-utensils"></i> Restaurant
                            @elseif($booking->booking_type === 'coffee')
                                <i class="fas fa-coffee"></i> Coffee Shop
                            @else
                                <i class="fas fa-tag"></i> {{ ucfirst($booking->booking_type) }}
                            @endif
                        </p>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Check-in / Date</p>
                        <p class="font-semibold text-gray-800">
                            <i class="fas fa-calendar"></i> {{ $booking->check_in_date ? \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') : 'N/A' }}
                        </p>
                    </div>

                    @if($booking->check_out_date)
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Check-out</p>
                        <p class="font-semibold text-gray-800">
                            <i class="fas fa-calendar-check"></i> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                        </p>
                    </div>
                    @endif

                    <!-- Guest Information -->
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Guest Name</p>
                        <p class="font-semibold text-gray-800">
                            <i class="fas fa-user"></i> {{ $booking->guest_name ?? auth()->user()->name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm mb-1">Contact</p>
                        <p class="font-semibold text-gray-800">
                            <i class="fas fa-phone"></i> {{ $booking->guest_phone ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm mb-1">Email</p>
                        <p class="font-semibold text-gray-800">
                            <i class="fas fa-envelope"></i> {{ $booking->guest_email ?? auth()->user()->email }}
                        </p>
                    </div>

                    <!-- Room Details (if applicable) -->
                    @if($booking->room)
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Room</p>
                        <p class="font-semibold text-gray-800">
                            <i class="fas fa-door-open"></i> {{ $booking->room->name }}
                        </p>
                    </div>
                    @endif

                    <!-- Guests Count -->
                    @if($booking->number_of_guests)
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Number of Guests</p>
                        <p class="font-semibold text-gray-800">
                            <i class="fas fa-users"></i> {{ $booking->number_of_guests }} Guest(s)
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Payment Information -->
                <div class="border-t pt-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Total Amount</p>
                            <p class="text-3xl font-bold text-[#F37021]">
                                ${{ number_format($booking->total_price, 2) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500 text-sm mb-1">Payment Status</p>
                            <span class="{{ $paymentColors[$booking->payment_status] ?? 'bg-gray-100 text-gray-800' }} px-4 py-2 rounded-lg text-sm font-bold inline-block">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>

                        @if($booking->payment_method)
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Payment Method</p>
                            <p class="font-semibold text-gray-800">
                                <i class="fas fa-credit-card"></i> {{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}
                            </p>
                        </div>
                        @endif

                        <div>
                            <p class="text-gray-500 text-sm mb-1">Booking Date</p>
                            <p class="font-semibold text-gray-800">
                                <i class="fas fa-clock"></i> {{ $booking->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Special Requests -->
                @if($booking->special_requests)
                <div class="border-t pt-6 mt-6">
                    <p class="text-gray-500 text-sm mb-2">Special Requests</p>
                    <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">
                        {{ $booking->special_requests }}
                    </p>
                </div>
                @endif

                <!-- Actions -->
                <div class="border-t pt-6 mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('bookings.show', $booking->id) }}"
                       class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition font-semibold inline-flex items-center gap-2">
                        <i class="fas fa-eye"></i> View Details
                    </a>

                    @if($booking->payment_status === 'paid')
                        <button onclick="alert('Invoice download feature coming soon!')"
                                class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition font-semibold inline-flex items-center gap-2">
                            <i class="fas fa-download"></i> Download Invoice
                        </button>
                    @endif

                    @if(in_array($booking->status, ['pending', 'confirmed']) && $booking->payment_status !== 'paid')
                        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                            @csrf
                            @method('POST')
                            <button type="submit"
                                    class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition font-semibold inline-flex items-center gap-2">
                                <i class="fas fa-times-circle"></i> Cancel Booking
                            </button>
                        </form>
                    @endif

                    @if($booking->payment_status === 'pending')
                        <button onclick="alert('Payment processing feature coming soon!')"
                                class="bg-[#F37021] text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition font-semibold inline-flex items-center gap-2">
                            <i class="fas fa-credit-card"></i> Pay Now
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-calendar-times fa-5x"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">No Bookings Yet</h3>
            <p class="text-gray-600 mb-6">You haven't made any bookings. Start exploring our facilities!</p>
            <a href="{{ route('home') }}"
               class="bg-[#F37021] text-white px-8 py-3 rounded-lg hover:bg-orange-600 transition font-semibold inline-flex items-center gap-2">
                <i class="fas fa-search"></i> Browse Facilities
            </a>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @endif
</div>

<!-- Font Awesome (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
