@extends('layouts.app')

@section('title', 'Contact Us - HFfinder')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap');

    * {
        font-family: 'Inter', sans-serif;
    }

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

    .animation-delay-4000 {
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(244, 106, 6, 0.4); }
        50% { box-shadow: 0 0 40px rgba(244, 106, 6, 0.8); }
    }

    .pulse-glow {
        animation: pulse-glow 2s ease-in-out infinite;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .hover-shake:hover {
        animation: shake 0.5s ease-in-out;
    }

    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .gradient-animate {
        background-size: 200% 200%;
        animation: gradient-shift 5s ease infinite;
    }

    .input-focus {
        transition: all 0.3s ease;
    }

    .input-focus:focus {
        transform: translateY(-2px) scale(1.01);
        border-color: #F46A06 !important;
        box-shadow: 0 0 0 4px rgba(244, 106, 6, 0.1), 0 10px 20px rgba(244, 106, 6, 0.1);
    }

    .card-hover {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .card-hover:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 20px 40px rgba(244, 106, 6, 0.25);
    }

    @keyframes bounce-in {
        0% { opacity: 0; transform: scale(0.3); }
        50% { opacity: 1; transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); }
    }

    .bounce-in {
        animation: bounce-in 0.6s ease-out;
    }

    @keyframes slide-in-bottom {
        0% { opacity: 0; transform: translateY(50px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .slide-in-bottom {
        animation: slide-in-bottom 0.6s ease-out;
    }

    @keyframes rotate-360 {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .hover-rotate:hover i {
        animation: rotate-360 0.6s ease-in-out;
    }

    .parallax-slow {
        transition: transform 0.2s ease-out;
    }

    @keyframes tada {
        0% { transform: scale(1); }
        10%, 20% { transform: scale(0.9) rotate(-3deg); }
        30%, 50%, 70%, 90% { transform: scale(1.1) rotate(3deg); }
        40%, 60%, 80% { transform: scale(1.1) rotate(-3deg); }
        100% { transform: scale(1) rotate(0); }
    }

    .hover-tada:hover {
        animation: tada 0.8s ease-in-out;
    }
</style>
@endpush

@section('content')
<!-- Hero Section with Particles -->
<section class="relative bg-gradient-to-br from-white via-orange-50/30 to-white overflow-hidden py-20">
    <!-- Animated Background Blobs -->
    <div class="absolute top-20 right-10 w-96 h-96 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute -bottom-20 left-10 w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 relative z-10">
        <!-- Hero Header -->
        <div class="text-center mb-16" data-aos="fade-up">
            <div class="inline-block p-4 bg-gradient-to-br from-orange-100 to-orange-200 rounded-3xl mb-6 pulse-glow animate-float">
                <i class="fa-solid fa-paper-plane text-6xl text-[#F46A06]"></i>
            </div>
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-[#211C24] mb-6 leading-tight">
                Let's Start a    <span class="text-[#F46A06]">Facility Finder</span>
            </h1>
            <p class="text-gray-600 text-xl lg:text-2xl max-w-3xl mx-auto leading-relaxed">
                We're here to help! Reach out to us and we'll get back to you within 24 hours. Your satisfaction is our priority.
            </p>

            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-6 max-w-3xl mx-auto mt-12">
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-4xl font-bold text-[#F46A06] mb-2">24/7</div>
                    <div class="text-sm text-gray-600 font-semibold">Support Available</div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-4xl font-bold text-[#F46A06] mb-2">&lt;1h</div>
                    <div class="text-sm text-gray-600 font-semibold">Response Time</div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-4xl font-bold text-[#F46A06] mb-2">100%</div>
                    <div class="text-sm text-gray-600 font-semibold">Satisfaction</div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid lg:grid-cols-5 gap-8 items-start">
            <!-- Contact Information - 2 Columns -->
            <div class="lg:col-span-2" data-aos="fade-right">
                <div class="bg-white rounded-3xl shadow-2xl p-8 card-hover">
                    <h2 class="text-3xl font-bold text-[#211C24] mb-8 flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F46A06] to-orange-600 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-address-book text-white text-xl"></i>
                        </div>
                        Get in Touch
                    </h2>

                    <div class="space-y-5">
                        <!-- Location -->
                        <div class="group flex items-start gap-4 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300 hover:translate-x-2 cursor-pointer">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-map-location-dot text-2xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-800 mb-2 flex items-center gap-2">
                                    Address
                                    <i class="fa-solid fa-arrow-right text-sm opacity-0 group-hover:opacity-100 transition-all"></i>
                                </h3>
                                <p class="text-gray-600 leading-relaxed">
                                    Cairo, Egypt<br>
                                    Tahrir Street, 5th Floor<br>
                                    Al Nour Building, No. 123
                                </p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="group flex items-start gap-4 p-5 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border-l-4 border-green-500 hover:shadow-xl transition-all duration-300 hover:translate-x-2 cursor-pointer">
                            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform hover-shake">
                                <i class="fa-solid fa-phone-volume text-2xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-800 mb-2 flex items-center gap-2">
                                    Phone
                                    <i class="fa-solid fa-arrow-right text-sm opacity-0 group-hover:opacity-100 transition-all"></i>
                                </h3>
                                <div class="space-y-2">
                                    <a href="tel:+201234567890" class="text-gray-600 hover:text-[#F46A06] transition-colors flex items-center gap-2 group/link">
                                        <i class="fa-solid fa-mobile-alt text-sm group-hover/link:animate-bounce"></i>
                                        +20 123 456 7890
                                    </a>
                                    <a href="tel:+200987654321" class="text-gray-600 hover:text-[#F46A06] transition-colors flex items-center gap-2 group/link">
                                        <i class="fa-solid fa-phone text-sm group-hover/link:animate-bounce"></i>
                                        +20 098 765 4321
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="group flex items-start gap-4 p-5 bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl border-l-4 border-[#F46A06] hover:shadow-xl transition-all duration-300 hover:translate-x-2 cursor-pointer">
                            <div class="w-14 h-14 bg-gradient-to-br from-[#F46A06] to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform hover-rotate">
                                <i class="fa-solid fa-envelope text-2xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-800 mb-2 flex items-center gap-2">
                                    Email
                                    <i class="fa-solid fa-arrow-right text-sm opacity-0 group-hover:opacity-100 transition-all"></i>
                                </h3>
                                <div class="space-y-2">
                                    <a href="mailto:info@hffinder.com" class="text-gray-600 hover:text-[#F46A06] transition-colors flex items-center gap-2 group/link">
                                        <i class="fa-solid fa-at text-sm group-hover/link:animate-ping"></i>
                                        info@hffinder.com
                                    </a>
                                    <a href="mailto:support@hffinder.com" class="text-gray-600 hover:text-[#F46A06] transition-colors flex items-center gap-2 group/link">
                                        <i class="fa-solid fa-headset text-sm group-hover/link:animate-ping"></i>
                                        support@hffinder.com
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Working Hours -->
                        <div class="group flex items-start gap-4 p-5 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl border-l-4 border-purple-500 hover:shadow-xl transition-all duration-300 hover:translate-x-2 cursor-pointer">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-clock text-2xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-800 mb-2 flex items-center gap-2">
                                    Working Hours
                                    <i class="fa-solid fa-arrow-right text-sm opacity-0 group-hover:opacity-100 transition-all"></i>
                                </h3>
                                <div class="space-y-2 text-gray-600">
                                    <p class="flex items-center gap-2">
                                        <i class="fa-solid fa-calendar-check text-green-500 text-sm animate-pulse"></i>
                                        Sat - Thu: 9:00 AM - 6:00 PM
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <i class="fa-solid fa-calendar-xmark text-red-500 text-sm"></i>
                                        Friday: Closed
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-8 pt-8 border-t-2 border-gray-100">
                        <h3 class="font-bold text-xl text-gray-800 mb-5 flex items-center gap-2">
                            <i class="fa-solid fa-share-nodes text-[#F46A06] animate-bounce"></i>
                            Connect With Us
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            <a href="#" class="group w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center text-white hover:scale-125 transition-all shadow-lg hover:shadow-2xl hover-tada">
                                <i class="fab fa-facebook-f text-xl"></i>
                            </a>
                            <a href="#" class="group w-14 h-14 bg-gradient-to-br from-sky-400 to-sky-600 rounded-xl flex items-center justify-center text-white hover:scale-125 transition-all shadow-lg hover:shadow-2xl hover-tada">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="#" class="group w-14 h-14 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center text-white hover:scale-125 transition-all shadow-lg hover:shadow-2xl hover-tada">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                            <a href="#" class="group w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center text-white hover:scale-125 transition-all shadow-lg hover:shadow-2xl hover-tada">
                                <i class="fab fa-linkedin-in text-xl"></i>
                            </a>
                            <a href="#" class="group w-14 h-14 bg-gradient-to-br from-green-500 to-green-700 rounded-xl flex items-center justify-center text-white hover:scale-125 transition-all shadow-lg hover:shadow-2xl hover-tada">
                                <i class="fab fa-whatsapp text-xl"></i>
                            </a>
                            <a href="#" class="group w-14 h-14 bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center text-white hover:scale-125 transition-all shadow-lg hover:shadow-2xl hover-tada">
                                <i class="fab fa-youtube text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form - 3 Columns -->
            <div class="lg:col-span-3" data-aos="fade-left">
                <div class="bg-white rounded-3xl shadow-2xl p-8 card-hover">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#F46A06] to-orange-600 rounded-2xl flex items-center justify-center animate-pulse">
                            <i class="fa-solid fa-paper-plane text-3xl text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-[#211C24]">Send Message</h2>
                            <p class="text-gray-600">We'll respond within 24 hours</p>
                        </div>
                    </div>

                    @if(session('success'))
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-2xl p-6 mb-8 flex items-center gap-4 shadow-lg bounce-in">
                        <div class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center flex-shrink-0 animate-bounce">
                            <i class="fa-solid fa-check text-3xl text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-green-800 text-lg mb-1">Success!</h4>
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-6" id="contactForm">
                        @csrf

                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="group">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-user text-[#F46A06] group-hover:animate-bounce"></i>
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="input-focus w-full px-5 py-4 border-2 border-gray-200 bg-white rounded-xl outline-none"
                                    placeholder="John Doe">
                                @error('name')
                                <p class="text-red-500 text-sm mt-2 flex items-center gap-1 slide-in-bottom">
                                    <i class="fa-solid fa-circle-exclamation animate-pulse"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="group">
                                <label for="email" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-envelope text-[#F46A06] group-hover:animate-bounce"></i>
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="input-focus w-full px-5 py-4 border-2 border-gray-200 bg-white rounded-xl outline-none"
                                    placeholder="john@example.com">
                                @error('email')
                                <p class="text-red-500 text-sm mt-2 flex items-center gap-1 slide-in-bottom">
                                    <i class="fa-solid fa-circle-exclamation animate-pulse"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Phone -->
                            <div class="group">
                                <label for="phone" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-phone text-[#F46A06] group-hover:animate-bounce"></i>
                                    Phone <span class="text-gray-400 text-xs">(Optional)</span>
                                </label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="input-focus w-full px-5 py-4 border-2 border-gray-200 bg-white rounded-xl outline-none"
                                    placeholder="+20 123 456 7890">
                            </div>

                            <!-- Subject -->
                            <div class="group">
                                <label for="subject" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-bookmark text-[#F46A06] group-hover:animate-bounce"></i>
                                    Subject <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                                    class="input-focus w-full px-5 py-4 border-2 border-gray-200 bg-white rounded-xl outline-none"
                                    placeholder="How can we help?">
                                @error('subject')
                                <p class="text-red-500 text-sm mt-2 flex items-center gap-1 slide-in-bottom">
                                    <i class="fa-solid fa-circle-exclamation animate-pulse"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="group">
                            <label for="message" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-message text-[#F46A06] group-hover:animate-bounce"></i>
                                Message <span class="text-red-500">*</span>
                            </label>
                            <textarea id="message" name="message" required rows="6"
                                class="input-focus w-full px-5 py-4 border-2 border-gray-200 bg-white rounded-xl outline-none resize-none"
                                placeholder="Tell us more about your inquiry...">{{ old('message') }}</textarea>
                            <div class="flex items-center justify-between mt-2">
                                <span id="charCount" class="text-sm text-gray-500">0 characters</span>
                                @error('message')
                                <p class="text-red-500 text-sm flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation animate-pulse"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="group relative w-full bg-gradient-to-r from-[#F46A06] via-orange-500 to-[#F46A06] hover:from-[#d85e05] hover:via-orange-600 hover:to-[#d85e05] text-white font-bold py-5 rounded-xl transition-all duration-300 shadow-lg hover:shadow-2xl hover:scale-105 overflow-hidden gradient-animate">
                            <span class="relative z-10 flex items-center justify-center gap-3">
                                <i class="fa-solid fa-paper-plane group-hover:translate-x-2 group-hover:-translate-y-1 transition-transform"></i>
                                <span>Send Message</span>
                                <i class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                            </span>
                        </button>
                    </form>

                    <!-- Info Boxes -->
                    <div class="grid md:grid-cols-2 gap-4 mt-8">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-xl p-4 hover:shadow-lg transition-all">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-shield-halved text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm mb-1">100% Secure</h4>
                                    <p class="text-gray-600 text-xs">Your data is encrypted and protected</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-xl p-4 hover:shadow-lg transition-all">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-clock text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm mb-1">Quick Response</h4>
                                    <p class="text-gray-600 text-xs">We reply within 1-2 hours</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="text-center mb-10" data-aos="fade-up">
            <h2 class="text-4xl font-bold text-[#211C24] mb-4">
                Find Us on <span class="text-[#F46A06]">Map</span>
            </h2>
            <p class="text-gray-600 text-lg">Visit our office or contact us anytime</p>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden h-96 card-hover" data-aos="zoom-in">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3453.123456789!2d31.235!3d30.044!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzDCsDAyJzM4LjQiTiAzMcKwMTQnMDYuMCJF!5e0!3m2!1sen!2seg!4v1234567890"
                width="100%"
                height="100%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-gradient-to-r from-[#F46A06] via-orange-500 to-[#d85e05] gradient-animate py-20 text-center text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full blur-3xl animate-blob"></div>
    <div class="absolute bottom-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl animate-blob animation-delay-2000"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4" data-aos="fade-up">
        <div class="inline-block p-4 bg-white/20 rounded-full mb-6 animate-float">
            <i class="fa-solid fa-headset text-5xl"></i>
        </div>
        <h2 class="text-4xl sm:text-5xl font-bold mb-6">Need Immediate Assistance?</h2>
        <p class="text-xl mb-8 opacity-90">Our support team is available 24/7 to help you with anything you need</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="tel:+201234567890" class="inline-flex items-center justify-center bg-white text-[#F46A06] font-bold px-10 py-5 rounded-xl shadow-2xl hover:shadow-3xl hover:scale-110 transition-all duration-300 hover-tada">
                <i class="fa-solid fa-phone mr-3 text-xl"></i>
                <span>Call Us Now</span>
            </a>
            <a href="mailto:support@hffinder.com" class="inline-flex items-center justify-center bg-white/10 backdrop-blur-sm text-white font-bold px-10 py-5 rounded-xl shadow-2xl hover:bg-white/20 hover:scale-110 transition-all duration-300 border-2 border-white/30 hover-tada">
                <i class="fa-solid fa-envelope mr-3 text-xl"></i>
                <span>Email Us</span>
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-[#211C24] text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- About -->
            <div data-aos="fade-up" data-aos-delay="0">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#F46A06] to-orange-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-map-location-dot text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold">HFfinder</h3>
                </div>
                <p class="text-gray-400 mb-6 leading-relaxed">
                    Your trusted platform for discovering the best hospitality facilities near you. Find, book, and enjoy amazing experiences.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#F46A06] transition-all hover:scale-110">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#F46A06] transition-all hover:scale-110">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#F46A06] transition-all hover:scale-110">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#F46A06] transition-all hover:scale-110">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div data-aos="fade-up" data-aos-delay="100">
                <h4 class="text-lg font-bold mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-link text-[#F46A06]"></i>
                    Quick Links
                </h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors flex items-center gap-2 group">
                        <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        Home
                    </a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors flex items-center gap-2 group">
                        <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        About Us
                    </a></li>
                    <li><a href="{{ route('services') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors flex items-center gap-2 group">
                        <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        Services
                    </a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors flex items-center gap-2 group">
                        <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        Contact
                    </a></li>
                </ul>
            </div>

            <!-- Services -->
            <div data-aos="fade-up" data-aos-delay="200">
                <h4 class="text-lg font-bold mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-cogs text-[#F46A06]"></i>
                    Our Services
                </h4>
                <ul class="space-y-3">
                    <li class="text-gray-400 flex items-start gap-2">
                        <i class="fa-solid fa-check text-[#F46A06] mt-1"></i>
                        <span>Hotel Booking</span>
                    </li>
                    <li class="text-gray-400 flex items-start gap-2">
                        <i class="fa-solid fa-check text-[#F46A06] mt-1"></i>
                        <span>Restaurant Reservations</span>
                    </li>
                    <li class="text-gray-400 flex items-start gap-2">
                        <i class="fa-solid fa-check text-[#F46A06] mt-1"></i>
                        <span>Coffee Shop Finder</span>
                    </li>
                    <li class="text-gray-400 flex items-start gap-2">
                        <i class="fa-solid fa-check text-[#F46A06] mt-1"></i>
                        <span>Transport Services</span>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div data-aos="fade-up" data-aos-delay="300">
                <h4 class="text-lg font-bold mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-envelope text-[#F46A06]"></i>
                    Newsletter
                </h4>
                <p class="text-gray-400 mb-4">Subscribe to get updates and special offers</p>
                <form class="space-y-3">
                    <input type="email" placeholder="Your email"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-[#F46A06] transition-all">
                    <button type="submit" class="w-full bg-gradient-to-r from-[#F46A06] to-orange-600 hover:from-[#d85e05] hover:to-orange-700 py-3 rounded-xl font-bold transition-all hover:scale-105">
                        <i class="fa-solid fa-paper-plane mr-2"></i>
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-white/10 pt-8 mt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-400 text-sm text-center md:text-left">
                    © {{ date('Y') }} HFfinder. All rights reserved. Made with <i class="fa-solid fa-heart text-red-500 animate-pulse"></i> in Egypt
                </p>
                <div class="flex gap-6 text-sm">
                    <a href="#" class="text-gray-400 hover:text-[#F46A06] transition-colors">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-[#F46A06] transition-colors">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-[#F46A06] transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="fixed bottom-8 right-8 bg-gradient-to-r from-[#F46A06] to-orange-600 text-white w-14 h-14 rounded-full shadow-2xl hover:scale-125 transition-all duration-300 opacity-0 pointer-events-none z-50 flex items-center justify-center group">
    <i class="fa-solid fa-arrow-up text-xl group-hover:-translate-y-1 transition-transform"></i>
</button>
@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 50
    });

    // Character Counter
    const messageField = document.getElementById('message');
    const charCount = document.getElementById('charCount');

    if (messageField && charCount) {
        messageField.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length} characters`;

            if (length < 10) {
                charCount.classList.add('text-red-500');
                charCount.classList.remove('text-gray-500', 'text-green-500');
            } else if (length >= 10 && length < 500) {
                charCount.classList.add('text-green-500');
                charCount.classList.remove('text-gray-500', 'text-red-500');
            } else {
                charCount.classList.add('text-gray-500');
                charCount.classList.remove('text-red-500', 'text-green-500');
            }
        });
    }

    // Form Validation with Animation
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        const inputs = contactForm.querySelectorAll('input, textarea');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() !== '') {
                    this.classList.add('border-green-400');
                    this.classList.remove('border-gray-200', 'border-red-400');
                } else if (this.hasAttribute('required')) {
                    this.classList.add('border-red-400');
                    this.classList.remove('border-gray-200', 'border-green-400');
                }
            });

            input.addEventListener('focus', function() {
                this.classList.remove('border-green-400', 'border-red-400');
                this.classList.add('border-gray-200');
            });
        });
    }

    // Back to Top Button
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTop.classList.remove('opacity-0', 'pointer-events-none');
            backToTop.classList.add('opacity-100', 'pointer-events-auto');
        } else {
            backToTop.classList.add('opacity-0', 'pointer-events-none');
            backToTop.classList.remove('opacity-100', 'pointer-events-auto');
        }
    });

    backToTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Parallax Effect for Hero
    document.addEventListener('mousemove', (e) => {
        const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
        const moveY = (e.clientY - window.innerHeight / 2) * 0.01;

        document.querySelectorAll('.parallax-slow').forEach(el => {
            el.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });
    });

    // Smooth Scroll for Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add Loading State to Form
    if (contactForm) {
        contactForm.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Sending...';
        });
    }
</script>
@endpush
