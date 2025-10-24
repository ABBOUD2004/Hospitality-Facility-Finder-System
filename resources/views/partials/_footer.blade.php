<footer class="bg-gradient-to-br from-[#211C24] to-[#1a1620] text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- About Section -->
            <div>
                <div class="flex items-center space-x-1 mb-6">
                    <span class="text-3xl font-bold text-white">HF</span>
                    <span class="text-xl font-semibold text-gray-300">finder</span>
                    <span class="text-[#F46A06] text-2xl font-bold">.</span>
                </div>
                <p class="text-gray-400 leading-relaxed mb-6">
                    Your trusted platform for discovering the best hospitality facilities.
                    Hotels, restaurants, and coffee shops all in one place.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-white/10 hover:bg-[#F46A06] rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 hover:bg-[#F46A06] rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 hover:bg-[#F46A06] rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 hover:bg-[#F46A06] rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold mb-6 text-white">Quick Links</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ url('/') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors duration-300 flex items-center group">
                            <i class="fa-solid fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#facilities') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors duration-300 flex items-center group">
                            <i class="fa-solid fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Facilities
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/about') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors duration-300 flex items-center group">
                            <i class="fa-solid fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            About Us
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/contact') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors duration-300 flex items-center group">
                            <i class="fa-solid fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Contact
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/faq') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors duration-300 flex items-center group">
                            <i class="fa-solid fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                            FAQ
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h3 class="text-lg font-bold mb-6 text-white">Our Services</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home', ['type' => 'hotel']) }}" class="text-gray-400 hover:text-[#F46A06] transition-colors duration-300 flex items-center group">
                            <i class="fa-solid fa-hotel text-xs mr-2 text-[#F46A06]"></i>
                            Hotels
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home', ['type' => 'restaurant']) }}" class="text-gray-400 hover:text-[#F46A06] transition-colors duration-300 flex items-center group">
                            <i class="fa-solid fa-utensils text-xs mr-2 text-[#F46A06]"></i>
                            Restaurants
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home', ['type' => 'coffee_shop']) }}" class="text-gray-400 hover:text-[#F46A06] transition-colors duration-300 flex items-center group">
                            <i class="fa-solid fa-mug-hot text-xs mr-2 text-[#F46A06]"></i>
                            Coffee Shops
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-[#F46A06] transition-colors duration-300 flex items-center group">
                            <i class="fa-solid fa-car text-xs mr-2 text-[#F46A06]"></i>
                            Transport Booking
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-[#F46A06] transition-colors duration-300 flex items-center group">
                            <i class="fa-solid fa-map-marked-alt text-xs mr-2 text-[#F46A06]"></i>
                            Interactive Map
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-bold mb-6 text-white">Contact Us</h3>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <i class="fa-solid fa-location-dot text-[#F46A06] mt-1 mr-3"></i>
                        <span class="text-gray-400">Kigali, Rwanda<br>KG 123 Street</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-phone text-[#F46A06] mt-1 mr-3"></i>
                        <a href="tel:+250123456789" class="text-gray-400 hover:text-[#F46A06] transition-colors">
                            +250 123 456 789
                        </a>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-envelope text-[#F46A06] mt-1 mr-3"></i>
                        <a href="mailto:info@hffinder.com" class="text-gray-400 hover:text-[#F46A06] transition-colors">
                            info@hffinder.com
                        </a>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-clock text-[#F46A06] mt-1 mr-3"></i>
                        <span class="text-gray-400">24/7 Available</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Newsletter Section -->
        <div class="border-t border-gray-700 pt-12 mb-12">
            <div class="max-w-2xl mx-auto text-center">
                <h3 class="text-2xl font-bold mb-3">Stay Updated</h3>
                <p class="text-gray-400 mb-6">Subscribe to our newsletter for the latest updates and offers</p>
                <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    <input type="email" placeholder="Enter your email" required class="flex-1 px-6 py-3 rounded-xl bg-white/10 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F46A06] focus:border-transparent transition-all">
                    <button type="submit" class="px-8 py-3 bg-[#F46A06] hover:bg-[#d85e05] rounded-xl font-semibold transition-all duration-300 hover:scale-105 shadow-lg">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-700 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-400 text-sm text-center md:text-left">
                    &copy; {{ date('Y') }} HFfinder. All rights reserved.
                </p>
                <div class="flex flex-wrap justify-center gap-6 text-sm">
                    <a href="{{ url('/privacy-policy') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors">
                        Privacy Policy
                    </a>
                    <a href="{{ url('/terms-conditions') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors">
                        Terms & Conditions
                    </a>
                    <a href="{{ url('/cookie-policy') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors">
                        Cookie Policy
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-8 right-8 w-12 h-12 bg-[#F46A06] hover:bg-[#d85e05] text-white rounded-full shadow-2xl opacity-0 invisible transition-all duration-300 hover:scale-110 z-40">
        <i class="fa-solid fa-arrow-up"></i>
    </button>
</footer>

<script>
// Back to Top Button
const backToTopBtn = document.getElementById('backToTop');

if (backToTopBtn) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopBtn.classList.remove('opacity-0', 'invisible');
            backToTopBtn.classList.add('opacity-100', 'visible');
        } else {
            backToTopBtn.classList.add('opacity-0', 'invisible');
            backToTopBtn.classList.remove('opacity-100', 'visible');
        }
    });

    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Newsletter Form
const newsletterForm = document.querySelector('footer form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const email = newsletterForm.querySelector('input[type="email"]').value;

        if (window.notify) {
            window.notify.success('Subscribed!', `Thank you for subscribing with ${email}`);
            newsletterForm.reset();
        } else {
            alert('Thank you for subscribing!');
            newsletterForm.reset();
        }
    });
}
</script>
