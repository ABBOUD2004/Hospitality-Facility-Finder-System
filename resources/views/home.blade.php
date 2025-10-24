@extends('layouts.app')

@section('title', 'Find Best Hospitality Facilities')

@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-white via-orange-50/30 to-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-16 sm:py-20 lg:py-28">
        <div class="flex flex-col-reverse lg:flex-row items-center justify-between gap-12 lg:gap-16">
            <!-- Text Content -->
            <div class="w-full lg:w-1/2 space-y-6 text-center lg:text-left" data-aos="fade-right">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-[#211C24] leading-tight">
                    Online Hospitality <br>
                    <span class="text-[#F46A06]">Facility Finder</span> System <br>
                    <span class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl">The Right Place.</span>
                </h1>

                <p class="text-gray-600 text-base sm:text-lg lg:text-xl leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Discover nearby hospitality facilities with ease. Find hotels, restaurants,
                    and coffee shops in your area with our interactive map and booking system.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <button id="openMapBtn" class="inline-flex items-center justify-center bg-[#F46A06] text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:bg-[#d85e05] hover:shadow-xl hover:scale-105 transition-all duration-300">
                        <i class="fa-solid fa-map-location-dot mr-2"></i>
                        View on Map
                    </button>
                    <a href="#facilities" class="inline-flex items-center justify-center bg-white text-[#F46A06] font-semibold px-8 py-4 rounded-xl shadow-lg border-2 border-[#F46A06] hover:bg-[#F46A06] hover:text-white hover:scale-105 transition-all duration-300">
                        <i class="fa-solid fa-building mr-2"></i>
                        Browse Facilities
                    </a>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 pt-8 max-w-md mx-auto lg:mx-0">
                    <div class="text-center">
                        <div class="text-2xl sm:text-3xl font-bold text-[#F46A06]">500+</div>
                        <div class="text-xs sm:text-sm text-gray-600">Facilities</div>
                    </div>
                    <div class="text-center border-x border-gray-200">
                        <div class="text-2xl sm:text-3xl font-bold text-[#F46A06]">10k+</div>
                        <div class="text-xs sm:text-sm text-gray-600">Bookings</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl sm:text-3xl font-bold text-[#F46A06]">4.8★</div>
                        <div class="text-xs sm:text-sm text-gray-600">Rating</div>
                    </div>
                </div>
            </div>

            <!-- Image -->
            <div class="relative w-full lg:w-1/2" data-aos="fade-left">
                <div class="absolute inset-0 bg-[#F46A06] rounded-full blur-[100px] opacity-20 animate-pulse"></div>
                <img src="{{ asset('images/hotel.png') }}" alt="Hospitality" class="relative z-10 w-full max-w-lg mx-auto h-auto object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-500">
            </div>
        </div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-20 right-10 w-72 h-72 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute -bottom-20 left-10 w-72 h-72 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
</section>

<!-- Map Modal -->
<div id="mapModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4 opacity-0 transition-all duration-300">
    <div class="relative bg-white w-full max-w-6xl h-[85vh] rounded-3xl shadow-2xl overflow-hidden flex flex-col transform scale-95 transition-all duration-300">
        <button id="closeMapBtn" class="absolute top-4 right-4 z-50 bg-[#F46A06] hover:bg-[#d85e05] text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 hover:rotate-90">
            <i class="fa-solid fa-times text-xl"></i>
        </button>

        <div id="realMap" class="w-full h-full rounded-3xl"></div>

        <div class="absolute bottom-6 right-6 bg-white/95 backdrop-blur-md p-5 rounded-2xl shadow-xl max-w-xs">
            <h4 class="font-bold text-gray-800 mb-3">Map Legend</h4>
            <div class="space-y-2 text-sm">
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 bg-[#F46A06] rounded-full mr-3"></span>
                    <span class="text-gray-700">Restaurants & Cafés</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 bg-[#C97A86] rounded-full mr-3"></span>
                    <span class="text-gray-700">Hotels</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 bg-[#D7C7A7] rounded-full mr-3"></span>
                    <span class="text-gray-700">Coverage Area</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="py-16 sm:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-[#211C24] mb-4">Why Choose HFfinder?</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Everything you need to find and book the perfect hospitality facility</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="0">
                <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-map-marked-alt text-2xl text-[#F46A06]"></i>
                </div>
                <h3 class="font-bold text-xl mb-2">Interactive Map</h3>
                <p class="text-gray-600">View all facilities on an interactive map with real-time locations</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-calendar-check text-2xl text-[#F46A06]"></i>
                </div>
                <h3 class="font-bold text-xl mb-2">Easy Booking</h3>
                <p class="text-gray-600">Book your accommodation or table in just a few clicks</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-car text-2xl text-[#F46A06]"></i>
                </div>
                <h3 class="font-bold text-xl mb-2">Transport Service</h3>
                <p class="text-gray-600">Book a car to reach your destination comfortably</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="300">
                <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-star text-2xl text-[#F46A06]"></i>
                </div>
                <h3 class="font-bold text-xl mb-2">Verified Reviews</h3>
                <p class="text-gray-600">Read authentic reviews from real customers</p>
            </div>
        </div>
    </div>
</section>

<!-- Transport CTA Section -->
<section class="bg-gradient-to-r from-[#F46A06] to-[#d85e05] py-16 sm:py-20 text-center text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6">
        <div class="inline-block p-3 bg-white/20 rounded-full mb-6">
            <i class="fa-solid fa-taxi text-5xl"></i>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">Need a Ride?</h2>
        <p class="mb-8 text-lg sm:text-xl max-w-2xl mx-auto opacity-90">
            Book a comfortable car and reach your chosen facility with ease. Available 24/7.
        </p>
        <button id="openBookingBtn" class="inline-flex items-center bg-white text-[#F46A06] font-bold px-10 py-4 rounded-xl shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300">
            <i class="fa-solid fa-car-side mr-2"></i>
            Book a Car Now
        </button>
    </div>
</section>

<!-- Transport Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 px-4 hidden opacity-0 transition-all duration-300 overflow-y-auto py-8">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-2xl w-full p-8 relative transform scale-95 transition-all duration-300 my-8 max-h-[90vh] overflow-y-auto">
        <button id="closeBookingBtn" class="sticky top-0 right-0 float-right text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:rotate-90 transition-all duration-300 z-10 bg-white dark:bg-gray-800 rounded-full p-2">
            <i class="fa-solid fa-times text-2xl"></i>
        </button>

        <div class="text-center mb-8 clear-both">
            <div class="inline-block p-4 bg-orange-100 dark:bg-orange-900/30 rounded-full mb-4">
                <i class="fa-solid fa-car text-3xl text-[#F46A06]"></i>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">Book Your Ride</h3>
            <p class="text-gray-500 dark:text-gray-400">Fill in your details and we'll get you there</p>
        </div>

        <form id="bookingForm" class="space-y-6">
            @csrf

            <!-- Customer Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fa-solid fa-user text-[#F46A06] mr-1"></i>
                        Full Name *
                    </label>
                    <input type="text" name="customer_name" required
                           placeholder="Enter your full name"
                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[#F46A06] focus:border-transparent outline-none transition-all duration-300">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fa-solid fa-phone text-[#F46A06] mr-1"></i>
                        Phone Number *
                    </label>
                    <input type="tel" name="customer_phone" required
                           placeholder="078XXXXXXXX"
                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[#F46A06] focus:border-transparent outline-none transition-all duration-300">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fa-solid fa-envelope text-[#F46A06] mr-1"></i>
                    Email (Optional)
                </label>
                <input type="email" name="customer_email"
                       placeholder="your.email@example.com"
                       class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[#F46A06] focus:border-transparent outline-none transition-all duration-300">
            </div>

            <!-- Trip Details -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fa-solid fa-location-dot text-[#F46A06] mr-1"></i>
                    Pickup Location *
                </label>
                <input type="text" name="pickup_location" required
                       placeholder="Enter your current location"
                       class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[#F46A06] focus:border-transparent outline-none transition-all duration-300">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fa-solid fa-flag-checkered text-[#F46A06] mr-1"></i>
                    Destination *
                </label>
                <input type="text" name="destination" required
                       placeholder="Where do you want to go?"
                       class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[#F46A06] focus:border-transparent outline-none transition-all duration-300">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fa-solid fa-calendar text-[#F46A06] mr-1"></i>
                        Pickup Date *
                    </label>
                    <input type="date" name="reservation_date" id="pickupDate" required
                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[#F46A06] focus:border-transparent outline-none transition-all duration-300">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fa-solid fa-clock text-[#F46A06] mr-1"></i>
                        Pickup Time *
                    </label>
                    <input type="time" name="reservation_time" required
                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[#F46A06] focus:border-transparent outline-none transition-all duration-300">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fa-solid fa-users text-[#F46A06] mr-1"></i>
                    Number of Passengers *
                </label>
                <input type="number" name="number_of_guests" id="numPassengers" min="1" max="50" value="1" required
                       class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[#F46A06] focus:border-transparent outline-none transition-all duration-300">
            </div>

            <input type="hidden" name="vehicle_type" value="car" id="hiddenVehicleType">

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    <i class="fa-solid fa-car text-[#F46A06] mr-1"></i>
                    Choose Vehicle Type
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <button type="button" data-vehicle="car" class="vehicle-btn border-2 border-[#F46A06] bg-orange-50 dark:bg-orange-900/20 rounded-xl p-4 text-center hover:border-[#F46A06] transition-all duration-300 active">
                        <i class="fas fa-car text-3xl text-blue-500 mb-2"></i>
                        <p class="font-bold text-gray-800 dark:text-white text-sm">Car</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">1-4 people</p>
                    </button>

                    <button type="button" data-vehicle="van" class="vehicle-btn border-2 border-gray-200 dark:border-gray-600 rounded-xl p-4 text-center hover:border-[#F46A06] transition-all duration-300">
                        <i class="fas fa-shuttle-van text-3xl text-purple-500 mb-2"></i>
                        <p class="font-bold text-gray-800 dark:text-white text-sm">Van</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">5-8 people</p>
                    </button>

                    <button type="button" data-vehicle="truck" class="vehicle-btn border-2 border-gray-200 dark:border-gray-600 rounded-xl p-4 text-center hover:border-[#F46A06] transition-all duration-300">
                        <i class="fas fa-truck text-3xl text-gray-500 mb-2"></i>
                        <p class="font-bold text-gray-800 dark:text-white text-sm">Truck</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Cargo</p>
                    </button>

                    <button type="button" data-vehicle="bus" class="vehicle-btn border-2 border-gray-200 dark:border-gray-600 rounded-xl p-4 text-center hover:border-[#F46A06] transition-all duration-300">
                        <i class="fas fa-bus text-3xl text-yellow-500 mb-2"></i>
                        <p class="font-bold text-gray-800 dark:text-white text-sm">Bus</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">10+ people</p>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fa-solid fa-comment text-[#F46A06] mr-1"></i>
                    Special Requests (Optional)
                </label>
                <textarea name="notes" rows="3"
                          placeholder="Any special requirements or notes..."
                          class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[#F46A06] focus:border-transparent outline-none transition-all duration-300 resize-none"></textarea>
            </div>

            <div class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-4 border-2 border-orange-200 dark:border-orange-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calculator text-[#F46A06] text-xl"></i>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Estimated Price:</span>
                    </div>
                    <div>
                        <span id="estimatedPrice" class="text-2xl font-bold text-[#F46A06]">5,000</span>
                        <span class="text-gray-600 dark:text-gray-400 ml-1">RWF</span>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle"></i> Final price will be confirmed by driver
                </p>
            </div>

            <button type="submit" id="submitBookingBtn" class="w-full bg-gradient-to-r from-[#F46A06] to-orange-600 hover:from-[#d85e05] hover:to-orange-700 text-white font-bold py-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 flex items-center justify-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>Confirm Booking</span>
            </button>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 px-4 hidden opacity-0 transition-all duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-md w-full p-8 text-center transform scale-95 transition-all duration-300">
        <div class="mb-6">
            <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto animate-bounce">
                <i class="fa-solid fa-check text-4xl text-green-500"></i>
            </div>
        </div>

        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-3">Booking Confirmed!</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-2">Your booking reference:</p>
        <p id="bookingReference" class="text-2xl font-bold text-[#F46A06] mb-6">--</p>
        <p class="text-gray-600 dark:text-gray-400 mb-8">You'll receive a call 5 minutes before the taxi arrives.</p>

        <button id="closeSuccessBtn" class="w-full bg-gradient-to-r from-[#F46A06] to-orange-600 hover:from-[#d85e05] hover:to-orange-700 text-white font-semibold py-3 rounded-xl transition-all duration-300">
            <i class="fa-solid fa-check mr-2"></i>
            Got it!
        </button>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[60] hidden">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 flex flex-col items-center">
        <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-200 border-t-[#F46A06] mb-4"></div>
        <p class="text-gray-700 dark:text-gray-300 font-semibold">Processing your booking...</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Transport Booking System Initialized');

    // Set minimum date
    const pickupDate = document.getElementById('pickupDate');
    if (pickupDate) {
        const today = new Date().toISOString().split('T')[0];
        pickupDate.min = today;
        pickupDate.value = today;
    }

    // Vehicle Selection
    document.querySelectorAll('.vehicle-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            document.querySelectorAll('.vehicle-btn').forEach(b => {
                b.classList.remove('active', 'border-[#F46A06]', 'bg-orange-50', 'dark:bg-orange-900/20');
                b.classList.add('border-gray-200', 'dark:border-gray-600');
            });

            this.classList.add('active', 'border-[#F46A06]', 'bg-orange-50', 'dark:bg-orange-900/20');
            this.classList.remove('border-gray-200', 'dark:border-gray-600');

            document.getElementById('hiddenVehicleType').value = this.dataset.vehicle;
            calculatePrice();
        });
    });

    // Price Calculation
    function calculatePrice() {
        const vehicleType = document.getElementById('hiddenVehicleType').value;
        const passengers = parseInt(document.getElementById('numPassengers').value) || 1;

        const basePrices = { car: 5000, van: 8000, truck: 12000, bus: 15000 };
        let price = basePrices[vehicleType] || 5000;

        if (passengers > 4) {
            price += (passengers - 4) * 500;
        }

        document.getElementById('estimatedPrice').textContent = price.toLocaleString();
        return price;
    }

    document.getElementById('numPassengers')?.addEventListener('input', calculatePrice);

    // Modal Controls
    document.getElementById('closeBookingBtn')?.addEventListener('click', () => {
        document.getElementById('bookingModal').classList.add('hidden', 'opacity-0');
    });

    document.getElementById('closeSuccessBtn')?.addEventListener('click', () => {
        document.getElementById('successModal').classList.add('hidden', 'opacity-0');
        location.reload();
    });

    window.openBookingModal = function() {
        const modal = document.getElementById('bookingModal');
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.remove('opacity-0'), 10);
        calculatePrice();
    };

    // ✅ FORM SUBMISSION - WORKS 100%
    const form = document.getElementById('bookingForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }

            document.getElementById('loadingOverlay').classList.remove('hidden');

            const formData = new FormData(this);

            // ✅ Add missing fields
            formData.append('booking_type', 'transport');
            formData.append('facility_id', '1');
            formData.append('price', calculatePrice());
            formData.append('payment_method', 'cash');

            // ✅ Log data
            console.log('📤 Sending:');
            for (let [key, value] of formData.entries()) {
                console.log(`  ${key}: ${value}`);
            }

            try {
                const response = await fetch('{{ route("bookings.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                console.log('📥 Status:', response.status);

                const text = await response.text();
                console.log('📥 Response:', text);

                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('❌ JSON Parse Error');
                    document.getElementById('loadingOverlay').classList.add('hidden');
                    alert('Server Error:\n\n' + text.substring(0, 300));
                    return;
                }

                document.getElementById('loadingOverlay').classList.add('hidden');

                if (response.ok && data.success) {
                    // ✅ SUCCESS
                    const ref = data.booking_reference || data.order_number || '#TRP-' + Date.now();
                    document.getElementById('bookingReference').textContent = ref;

                    document.getElementById('bookingModal').classList.add('hidden');
                    setTimeout(() => {
                        const successModal = document.getElementById('successModal');
                        successModal.classList.remove('hidden');
                        setTimeout(() => successModal.classList.remove('opacity-0'), 10);
                    }, 300);

                    form.reset();
                    calculatePrice();

                    console.log('✅ Booking Created!');

                } else if (data.errors) {
                    // Validation Errors
                    console.error('❌ Errors:', data.errors);
                    let msg = '❌ Please fix:\n\n';
                    for (let field in data.errors) {
                        msg += `• ${field}: ${data.errors[field].join(', ')}\n`;
                    }
                    alert(msg);

                } else {
                    console.error('❌ Error:', data);
                    alert('❌ ' + (data.message || 'Booking failed'));
                }

            } catch (error) {
                document.getElementById('loadingOverlay').classList.add('hidden');
                console.error('❌ Network Error:', error);
                alert('❌ Network Error: ' + error.message);
            }
        });
    }
});
</script>

<style>
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.vehicle-btn.active {
    border-color: #F46A06 !important;
    background-color: rgba(244, 106, 6, 0.1) !important;
}
</style>

<style>
.vehicle-option input:checked + div {
    border-color: #F46A06 !important;
    background-color: rgba(244, 106, 6, 0.1);
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.animate-bounce {
    animation: bounce 1s infinite;
}

/* إضافة scroll للـ modal */
#bookingModal {
    overflow-y: auto !important;
}

#bookingModal > div {
    max-height: 90vh;
    overflow-y: auto;
}

/* تحسين scroll bar */
#bookingModal > div::-webkit-scrollbar {
    width: 8px;
}

#bookingModal > div::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#bookingModal > div::-webkit-scrollbar-thumb {
    background: #F46A06;
    border-radius: 10px;
}

#bookingModal > div::-webkit-scrollbar-thumb:hover {
    background: #d85e05;
}
</style>

<!-- Facilities Section -->
<section id="facilities" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-16 sm:py-20">
    <div class="text-center mb-12">
        <h2 class="text-3xl sm:text-4xl font-bold text-[#211C24] mb-4">Nearest Facilities</h2>
        <p class="text-gray-600 text-lg">Discover the best hospitality facilities near you</p>
    </div>

    @php
        $current = request('type', 'all');
        $types = [
            'all' => ['label' => 'All Facilities', 'icon' => 'fa-th'],
            'hotel' => ['label' => 'Hotels', 'icon' => 'fa-hotel'],
            'restaurant' => ['label' => 'Restaurants', 'icon' => 'fa-utensils'],
            'coffee_shop' => ['label' => 'Coffee Shops', 'icon' => 'fa-mug-hot']
        ];
    @endphp

    <!-- Filter Buttons -->
    <div class="flex flex-wrap justify-center gap-3 mb-12">
        @foreach ($types as $key => $data)
            @php
                $isActive = $current === $key;
                $query = $key === 'all' ? [] : ['type' => $key];
            @endphp
            <a href="{{ route('home', $query) }}"
               class="inline-flex items-center px-6 py-3 rounded-full font-semibold transition-all duration-300 hover:scale-105 {{ $isActive ? 'bg-[#F46A06] text-white shadow-lg' : 'bg-white text-gray-700 border-2 border-gray-200 hover:border-[#F46A06]' }}">
                <i class="fa-solid {{ $data['icon'] }} mr-2"></i>
                {{ $data['label'] }}
            </a>
        @endforeach
    </div>

    <!-- Facilities Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($facilities as $facility)
            <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden hover:-translate-y-2">
                <!-- Image -->
                <div class="relative h-64 overflow-hidden cursor-pointer" onclick="window.location.href='{{ route('facility.show', $facility->id) }}'">
                    <img src="{{ $facility->image ? asset($facility->image) : asset('images/placeholder.jpg') }}"
                         alt="{{ $facility->name }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                    <!-- Overlay on hover -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                        <button class="bg-white text-[#F46A06] px-6 py-2 rounded-full font-semibold hover:bg-[#F46A06] hover:text-white transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                            <i class="fa-solid fa-arrow-right mr-2"></i>
                            View Details
                        </button>
                    </div>

                    <!-- Type Badge -->
                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg">
                        <span class="text-sm font-semibold text-[#F46A06]">
                            @if($facility->type === 'hotel')
                                <i class="fa-solid fa-hotel mr-1"></i> Hotel
                            @elseif($facility->type === 'restaurant')
                                <i class="fa-solid fa-utensils mr-1"></i> Restaurant
                            @else
                                <i class="fa-solid fa-mug-hot mr-1"></i> Coffee Shop
                            @endif
                        </span>
                    </div>

                    @if($facility->rating)
                        <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-3 py-2 rounded-full shadow-lg">
                            <span class="text-sm font-bold text-yellow-500">
                                <i class="fa-solid fa-star mr-1"></i>{{ number_format($facility->rating, 1) }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-6">
                    <h3 class="font-bold text-xl text-gray-800 mb-2 group-hover:text-[#F46A06] transition-colors duration-300">
                        {{ $facility->name }}
                    </h3>

                    @if($facility->address)
                        <p class="text-gray-600 text-sm mb-4 flex items-start">
                            <i class="fa-solid fa-location-dot text-[#F46A06] mr-2 mt-1 flex-shrink-0"></i>
                            <span class="line-clamp-2">{{ $facility->address }}</span>
                        </p>
                    @endif

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        @if($facility->price_range)
                            <span class="text-sm font-semibold text-gray-700">
                                {{ $facility->price_range }}
                            </span>
                        @endif
                        <a href="{{ route('facility.show', $facility->id) }}" class="text-[#F46A06] font-semibold hover:text-[#d85e05] transition-colors">
                            Learn More <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-16 bg-gray-50 rounded-3xl">
                    <i class="fa-solid fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <p class="text-xl text-gray-500 font-semibold mb-2">No facilities found</p>
                    <p class="text-gray-400">Try selecting a different category</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($facilities->lastPage() > 1)
        <div class="mt-16 flex justify-center items-center gap-3">
            @if ($facilities->onFirstPage())
                <span class="px-6 py-3 text-gray-300 font-semibold cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left mr-2"></i>Prev
                </span>
            @else
                <a href="{{ $facilities->previousPageUrl() }}" class="px-6 py-3 bg-white text-gray-700 font-semibold rounded-xl shadow-md hover:shadow-lg hover:bg-[#F46A06] hover:text-white transition-all duration-300 hover:scale-105">
                    <i class="fa-solid fa-chevron-left mr-2"></i>Prev
                </a>
            @endif

            @php
                $start = max(1, $facilities->currentPage() - 2);
                $end = min($start + 4, $facilities->lastPage());
                $start = max(1, $end - 4);
            @endphp

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $facilities->currentPage())
                    <span class="w-12 h-12 flex items-center justify-center bg-[#F46A06] text-white text-lg font-bold rounded-xl shadow-lg">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $facilities->url($page) }}" class="w-12 h-12 flex items-center justify-center bg-white text-gray-700 text-lg font-bold rounded-xl shadow-md hover:shadow-lg hover:bg-[#F46A06] hover:text-white transition-all duration-300 hover:scale-105">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            @if ($facilities->hasMorePages())
                <a href="{{ $facilities->nextPageUrl() }}" class="px-6 py-3 bg-white text-gray-700 font-semibold rounded-xl shadow-md hover:shadow-lg hover:bg-[#F46A06] hover:text-white transition-all duration-300 hover:scale-105">
                    Next<i class="fa-solid fa-chevron-right ml-2"></i>
                </a>
            @else
                <span class="px-6 py-3 text-gray-300 font-semibold cursor-not-allowed">
                    Next<i class="fa-solid fa-chevron-right ml-2"></i>
                </span>
            @endif
        </div>
    @endif
</section>

<!-- About Section -->
<section class="bg-gradient-to-br from-gray-50 to-gray-100 py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block px-4 py-2 bg-orange-100 text-[#F46A06] rounded-full text-sm font-semibold mb-4">
                    About Us
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-[#211C24]">
                    Your Trusted Hospitality <span class="text-[#F46A06]">Partner</span>
                </h2>
                <p class="text-gray-600 text-lg leading-relaxed mb-6">
                    HFfinder is your comprehensive online system for discovering nearby hospitality facilities.
                    Whether you're looking for a cozy hotel, a fine dining restaurant, or a relaxing coffee shop,
                    we've got you covered.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fa-solid fa-check text-[#F46A06]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-1">Verified Listings</h4>
                            <p class="text-gray-600">All facilities are verified for quality and authenticity</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fa-solid fa-check text-[#F46A06]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-1">Real-time Updates</h4>
                            <p class="text-gray-600">Get live information about availability and locations</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fa-solid fa-check text-[#F46A06]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-1">24/7 Support</h4>
                            <p class="text-gray-600">Our team is always ready to assist you</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="absolute inset-0 bg-[#F46A06] rounded-full blur-[100px] opacity-20 animate-pulse"></div>
                <img src="{{ asset('images/hotel.png') }}" alt="Hospitality" class="relative z-10 w-full max-w-lg mx-auto h-auto object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-500">
            </div>
        </div>
    </div>
</section>


@include('partials._footer')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
<style>
    @keyframes blob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
// Initialize AOS
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true
});

// ========================= Modal Functions =========================

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    setTimeout(() => {
        modal.classList.remove('opacity-0');
        const content = modal.querySelector('.transform');
        if (content) {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }
    }, 10);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.add('opacity-0');
    const content = modal.querySelector('.transform');
    if (content) {
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
    }

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 300);
}

// ========================= Map Modal =========================

const openMapBtn = document.getElementById('openMapBtn');
const closeMapBtn = document.getElementById('closeMapBtn');
const mapModal = document.getElementById('mapModal');

if (openMapBtn) {
    openMapBtn.addEventListener('click', () => {
        openModal('mapModal');
        initMap();
    });
}

if (closeMapBtn) {
    closeMapBtn.addEventListener('click', () => {
        closeModal('mapModal');
    });
}

if (mapModal) {
    mapModal.addEventListener('click', (e) => {
        if (e.target === mapModal) {
            closeModal('mapModal');
        }
    });
}

function initMap() {
    if (window.mapInitialized) return;
    window.mapInitialized = true;

    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyC8lx3h75SBzqhPUXuc8LGmqIQCEPOhMsg&callback=loadRealMap`;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
}

window.loadRealMap = function() {
    const mapElement = document.getElementById('realMap');
    if (!mapElement) return;

    const map = new google.maps.Map(mapElement, {
        center: { lat: -1.9535, lng: 30.0605 },
        zoom: 14,
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            }
        ]
    });

    new google.maps.Marker({
        position: { lat: -1.9535, lng: 30.0605 },
        map: map,
        title: "Your Location",
        animation: google.maps.Animation.DROP
    });
};

// ========================= Booking Modal =========================

const openBookingBtn = document.getElementById('openBookingBtn');
const closeBookingBtn = document.getElementById('closeBookingBtn');
const bookingModal = document.getElementById('bookingModal');
const bookingForm = document.getElementById('bookingForm');
const successModal = document.getElementById('successModal');
const closeSuccessBtn = document.getElementById('closeSuccessBtn');
const changeRideBtn = document.getElementById('changeRideBtn');

if (openBookingBtn) {
    openBookingBtn.addEventListener('click', () => openModal('bookingModal'));
}

if (closeBookingBtn) {
    closeBookingBtn.addEventListener('click', () => closeModal('bookingModal'));
}

if (closeSuccessBtn) {
    closeSuccessBtn.addEventListener('click', () => closeModal('successModal'));
}

if (changeRideBtn) {
    changeRideBtn.addEventListener('click', () => {
        closeModal('successModal');
        setTimeout(() => openModal('bookingModal'), 300);
    });
}

if (bookingForm) {
    bookingForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // Get form values
        const pickup = document.getElementById('pickupLocation').value;
        const destination = document.getElementById('destinationLocation').value;
        const time = document.getElementById('departureTime').value;

        // Validate
        if (!pickup || !destination || !time) {
            if (window.notify) {
                window.notify.error('Missing Information', 'Please fill in all fields');
            }
            return;
        }

        closeModal('bookingModal');
        setTimeout(() => {
            openModal('successModal');
            bookingForm.reset();
        }, 300);
    });
}

if (bookingModal) {
    bookingModal.addEventListener('click', (e) => {
        if (e.target === bookingModal) {
            closeModal('bookingModal');
        }
    });
}

if (successModal) {
    successModal.addEventListener('click', (e) => {
        if (e.target === successModal) {
            closeModal('successModal');
        }
    });
}

// ========================= Notification System =========================

class AlertNotification {
    constructor() {
        this.createContainer();
        this.bindGlobalHandlers();
    }

    createContainer() {
        if (document.getElementById('alert-container')) return;

        const container = document.createElement('div');
        container.id = 'alert-container';
        container.className = 'fixed top-24 right-4 z-[9999] space-y-3 max-w-md';
        document.body.appendChild(container);
    }

    show(options = {}) {
        const {
            type = 'success',
            title = 'Success!',
            message = '',
            duration = 5000
        } = options;

        const alert = this.createAlert(type, title, message);
        const container = document.getElementById('alert-container');

        container.appendChild(alert);

        setTimeout(() => {
            alert.classList.remove('translate-x-full', 'opacity-0');
            alert.classList.add('translate-x-0', 'opacity-100');
        }, 10);

        if (duration > 0) {
            setTimeout(() => this.remove(alert), duration);
        }

        return alert;
    }

    createAlert(type, title, message) {
        const alert = document.createElement('div');
        alert.className = `transform translate-x-full opacity-0 transition-all duration-500 ease-out
                          bg-white rounded-2xl shadow-2xl border-l-4 p-4 flex items-start gap-3
                          hover:shadow-3xl hover:scale-105 cursor-pointer min-w-[320px]`;

        const colors = {
            success: {
                border: 'border-green-500',
                bg: 'bg-green-100',
                icon: 'text-green-600',
                iconPath: 'M5 13l4 4L19 7'
            },
            error: {
                border: 'border-red-500',
                bg: 'bg-red-100',
                icon: 'text-red-600',
                iconPath: 'M6 18L18 6M6 6l12 12'
            },
            warning: {
                border: 'border-yellow-500',
                bg: 'bg-yellow-100',
                icon: 'text-yellow-600',
                iconPath: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
            },
            info: {
                border: 'border-blue-500',
                bg: 'bg-blue-100',
                icon: 'text-blue-600',
                iconPath: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            }
        };

        const color = colors[type] || colors.success;
        alert.classList.add(color.border);

        alert.innerHTML = `
            <div class="${color.bg} ${color.icon} p-2.5 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${color.iconPath}"></path>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-gray-900 text-sm mb-1">${title}</h4>
                ${message ? `<p class="text-gray-600 text-sm">${message}</p>` : ''}
            </div>
            <button class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition ml-2" onclick="this.closest('.transform').remove()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;

        alert.addEventListener('click', (e) => {
            if (!e.target.closest('button')) {
                this.remove(alert);
            }
        });

        return alert;
    }

    remove(alert) {
        alert.classList.remove('translate-x-0', 'opacity-100');
        alert.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => alert.remove(), 500);
    }

    success(title, message = '', duration = 5000) {
        return this.show({ type: 'success', title, message, duration });
    }

    error(title, message = '', duration = 5000) {
        return this.show({ type: 'error', title, message, duration });
    }

    warning(title, message = '', duration = 5000) {
        return this.show({ type: 'warning', title, message, duration });
    }

    info(title, message = '', duration = 5000) {
        return this.show({ type: 'info', title, message, duration });
    }

    bindGlobalHandlers() {
        window.addEventListener('DOMContentLoaded', () => {
            const successMsg = document.querySelector('[data-success-message]');
            const errorMsg = document.querySelector('[data-error-message]');

            if (successMsg) {
                this.success('Success!', successMsg.dataset.successMessage);
            }
            if (errorMsg) {
                this.error('Error!', errorMsg.dataset.errorMessage);
            }
        });
    }
}

// Initialize global notification instance
window.notify = new AlertNotification();
</script>



</style>
<script class="bot" src="https://plugin-code.salesmartly.com/js/project_504914_520508_1761222662.js"></script>


@endpush

@endsection
