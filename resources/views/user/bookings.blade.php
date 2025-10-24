@extends('layouts.app')

@section('content')

<!-- Hero Header -->
<div class="bg-gradient-to-r from-[#F37021] to-orange-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-4xl font-bold mb-2">My Bookings</h1>
        <p class="text-orange-100">Track and manage all your reservations in one place</p>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-6 py-12">

    @if($bookings->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-3">No Bookings Yet</h2>
            <p class="text-gray-600 mb-8">Start exploring and book your first experience!</p>
            <a href="{{ route('home') }}"
               class="inline-block bg-[#F37021] text-white px-8 py-3 rounded-lg hover:bg-orange-600 transition font-semibold">
                Explore Facilities
            </a>
        </div>
    @else
        <!-- Bookings Grid -->
        <div class="space-y-6">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-[1.02] transition-all duration-300">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start gap-6">

                            <!-- Left Side: Booking Info -->
                            <div class="flex-1 space-y-4">
                                <!-- Type & Status Badges -->
                                <div class="flex flex-wrap items-center gap-3">
                                    {!! $booking->type_badge !!}
                                    {!! $booking->status_badge !!}
                                    <span class="text-sm text-gray-500">Booking #{{ $booking->id }}</span>
                                </div>

                                <!-- Main Info -->
                                <div>
                                    @if($booking->booking_type == 'hotel')
                                        <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $booking->facility->name ?? 'Hotel Booking' }}</h3>
                                        <p class="text-gray-600">{{ $booking->room->name ?? 'Room' }} • {{ $booking->number_of_guests }} guests</p>
                                    @elseif($booking->booking_type == 'transport')
                                        <h3 class="text-2xl font-bold text-gray-900 mb-1">Transport Booking</h3>
                                        <p class="text-gray-600">{{ $booking->pickup_location }} → {{ $booking->destination }}</p>
                                    @else
                                        <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $booking->facility->name ?? 'Reservation' }}</h3>
                                        <p class="text-gray-600">Table for {{ $booking->number_of_guests }} guests</p>
                                    @endif
                                </div>

                                <!-- Date & Time -->
                                <div class="flex flex-wrap items-center gap-6 text-sm">
                                    @if($booking->booking_type == 'hotel')
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <svg class="w-5 h-5 text-[#F37021]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="font-medium">Check-in:</span>
                                            <span>{{ $booking->check_in_date?->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <svg class="w-5 h-5 text-[#F37021]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="font-medium">Check-out:</span>
                                            <span>{{ $booking->check_out_date?->format('M d, Y') }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <svg class="w-5 h-5 text-[#F37021]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="font-medium">Date:</span>
                                            <span>{{ $booking->reservation_date?->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <svg class="w-5 h-5 text-[#F37021]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="font-medium">Time:</span>
                                            <span>{{ $booking->reservation_time }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Contact Info -->
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $booking->guest_name }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        {{ $booking->guest_phone }}
                                    </span>
                                </div>

                                @if($booking->total_price)
                                    <div class="text-2xl font-bold text-[#F37021]">
                                        ${{ number_format($booking->total_price, 2) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Right Side: Status Info -->
                            <div class="w-full md:w-auto">
                                @if($booking->status == 'pending')
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
                                        <div class="w-16 h-16 mx-auto mb-3 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-yellow-800 font-semibold mb-1">Under Review</p>
                                        <p class="text-sm text-yellow-600">We're processing your booking</p>
                                    </div>
                                @elseif($booking->status == 'confirmed')
                                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
                                        <div class="w-16 h-16 mx-auto mb-3 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-blue-800 font-semibold mb-1">Confirmed!</p>
                                        <p class="text-sm text-blue-600">Your booking is confirmed</p>
                                    </div>
                                @elseif($booking->status == 'completed')
                                    <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                                        <div class="w-16 h-16 mx-auto mb-3 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <p class="text-green-800 font-semibold mb-1">Completed</p>
                                        <p class="text-sm text-green-600">Thank you for visiting!</p>
                                    </div>
                                @else
                                    <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                                        <div class="w-16 h-16 mx-auto mb-3 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <p class="text-red-800 font-semibold mb-1">Cancelled</p>
                                        <p class="text-sm text-red-600">This booking was cancelled</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Special Requests -->
                        @if($booking->special_requests)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-700 mb-1">Special Requests:</p>
                                <p class="text-sm text-gray-600">{{ $booking->special_requests }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-3 flex justify-between items-center text-sm text-gray-500">
                        <span>Booked on {{ $booking->created_at->format('M d, Y \a\t h:i A') }}</span>
                        @if($booking->status == 'pending' && $booking->booking_type == 'transport')
                            <span class="text-[#F37021] font-medium">Driver will contact you soon</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @endif
    @endif
</div>

@includeWhen(true, 'partials._footer')

@endsection
