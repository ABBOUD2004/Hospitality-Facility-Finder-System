@extends('layouts.app')

@section('title', 'My Profile - HFfinder')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap');

    * {
        font-family: 'Inter', sans-serif;
    }

    @keyframes slideDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-15px); } }
    @keyframes blob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .animate-slide { animation: slideDown 0.6s ease-out; }
    .animate-fade { animation: fadeIn 0.8s ease-out; }
    .float-animation { animation: float 4s ease-in-out infinite; }
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }
    .gradient-animate { background-size: 200% 200%; animation: gradientShift 5s ease infinite; }

    .stat-card {
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .stat-card:hover::before { left: 100%; }
    .stat-card:hover { transform: translateY(-15px) scale(1.05); box-shadow: 0 25px 60px rgba(244, 106, 6, 0.25); }

    .profile-avatar {
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        position: relative;
    }

    .profile-avatar::after {
        content: '';
        position: absolute;
        inset: -5px;
        border-radius: 50%;
        background: linear-gradient(45deg, #F46A06, #ff8533, #F46A06);
        opacity: 0;
        z-index: -1;
        transition: opacity 0.3s;
        animation: rotate 3s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .profile-avatar:hover::after { opacity: 1; }
    .profile-avatar:hover { transform: scale(1.15) rotate(5deg); }

    .tab-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .tab-btn::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 4px;
        background: linear-gradient(90deg, #F46A06, #ff8533);
        transition: all 0.3s;
        transform: translateX(-50%);
    }

    .tab-btn.active::after { width: 100%; }

    .booking-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .booking-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(244, 106, 6, 0.1), transparent);
        transition: left 0.5s;
    }

    .booking-card:hover::before { left: 100%; }
    .booking-card:hover { transform: translateX(5px); box-shadow: 0 10px 30px rgba(244, 106, 6, 0.2); }

    .input-focus {
        transition: all 0.3s ease;
    }

    .input-focus:focus {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(244, 106, 6, 0.2);
        border-color: #F46A06 !important;
    }

    .card-hover {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .card-hover:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 20px 40px rgba(244, 106, 6, 0.25);
    }

    ::-webkit-scrollbar { width: 10px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #F46A06, #ff8533); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: linear-gradient(180deg, #d85e05, #F46A06); }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-white via-orange-50/30 to-white py-8 px-4">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Enhanced Header -->
        <div class="animate-slide relative bg-gradient-to-r from-[#F46A06] via-orange-500 to-[#d85e05] gradient-animate rounded-3xl shadow-2xl p-8 text-white overflow-hidden">
            <!-- Animated Background Circles -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -mr-48 -mt-48 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/10 rounded-full blur-3xl -ml-40 -mb-40 float-animation"></div>
            <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-white/5 rounded-full blur-2xl transform -translate-x-1/2 -translate-y-1/2"></div>

            <div class="relative z-10">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center float-animation">
                            <i class="fas fa-user-circle text-6xl drop-shadow-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-5xl md:text-6xl font-black mb-2 drop-shadow-lg">My Profile</h1>
                            <p class="text-white/90 text-xl">Manage your account & preferences</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="downloadProfile()" class="bg-white/20 hover:bg-white/30 backdrop-blur-md px-6 py-3 rounded-xl font-bold transition-all hover:scale-105 flex items-center gap-2">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <a href="{{ route('home') }}" class="bg-white/20 hover:bg-white/30 backdrop-blur-md px-6 py-3 rounded-xl font-bold transition-all hover:scale-105 flex items-center gap-2">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="animate-slide bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-2xl p-6 shadow-xl" data-aos="fade-down">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-4 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-green-800 font-bold text-lg mb-1">Success!</p>
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="animate-slide bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-2xl p-6 shadow-xl" data-aos="fade-down">
            <div class="flex items-center gap-4">
                <div class="bg-red-100 p-4 rounded-full">
                    <i class="fas fa-exclamation-circle text-red-600 text-3xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-red-800 font-bold text-lg mb-1">Error!</p>
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="stat-card bg-gradient-to-br from-[#F46A06] via-orange-500 to-orange-600 rounded-2xl shadow-2xl p-6 text-white cursor-pointer" data-aos="fade-up" data-aos-delay="0">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm opacity-90 font-semibold uppercase tracking-wide mb-1">Total</p>
                        <h3 class="text-5xl font-black">{{ $stats['total_bookings'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-white/20 p-5 rounded-2xl backdrop-blur-sm">
                        <i class="fas fa-clipboard-list text-4xl"></i>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-sm opacity-90">
                    <i class="fas fa-chart-line"></i>
                    <span>All Bookings</span>
                </div>
            </div>

            <div class="stat-card bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-500 rounded-2xl shadow-2xl p-6 text-white cursor-pointer" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm opacity-90 font-semibold uppercase tracking-wide mb-1">Pending</p>
                        <h3 class="text-5xl font-black">{{ $stats['pending_bookings'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-white/20 p-5 rounded-2xl backdrop-blur-sm">
                        <i class="fas fa-clock text-4xl"></i>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-sm opacity-90">
                    <i class="fas fa-hourglass-half"></i>
                    <span>Awaiting Confirmation</span>
                </div>
            </div>

            <div class="stat-card bg-gradient-to-br from-blue-500 via-blue-600 to-cyan-600 rounded-2xl shadow-2xl p-6 text-white cursor-pointer" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm opacity-90 font-semibold uppercase tracking-wide mb-1">Confirmed</p>
                        <h3 class="text-5xl font-black">{{ $stats['confirmed_bookings'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-white/20 p-5 rounded-2xl backdrop-blur-sm">
                        <i class="fas fa-check-circle text-4xl"></i>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-sm opacity-90">
                    <i class="fas fa-thumbs-up"></i>
                    <span>Ready to Go</span>
                </div>
            </div>

            <div class="stat-card bg-gradient-to-br from-green-500 via-green-600 to-emerald-600 rounded-2xl shadow-2xl p-6 text-white cursor-pointer" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm opacity-90 font-semibold uppercase tracking-wide mb-1">Completed</p>
                        <h3 class="text-5xl font-black">{{ $stats['completed_bookings'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-white/20 p-5 rounded-2xl backdrop-blur-sm">
                        <i class="fas fa-star text-4xl"></i>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-sm opacity-90">
                    <i class="fas fa-trophy"></i>
                    <span>Finished Successfully</span>
                </div>
            </div>

            <div class="stat-card bg-gradient-to-br from-red-500 via-red-600 to-pink-600 rounded-2xl shadow-2xl p-6 text-white cursor-pointer" data-aos="fade-up" data-aos-delay="400">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm opacity-90 font-semibold uppercase tracking-wide mb-1">Cancelled</p>
                        <h3 class="text-5xl font-black">{{ $stats['cancelled_bookings'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-white/20 p-5 rounded-2xl backdrop-blur-sm">
                        <i class="fas fa-times-circle text-4xl"></i>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-sm opacity-90">
                    <i class="fas fa-ban"></i>
                    <span>Cancelled Orders</span>
                </div>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="animate-fade bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-orange-100 card-hover" data-aos="fade-up">
            <div class="bg-gradient-to-r from-[#F46A06] via-orange-500 to-[#d85e05] p-8 relative overflow-hidden gradient-animate">
                <!-- Animated Background -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute w-64 h-64 bg-white/20 rounded-full blur-3xl top-0 right-0 animate-blob"></div>
                    <div class="absolute w-64 h-64 bg-white/20 rounded-full blur-3xl bottom-0 left-0 animate-blob animation-delay-2000"></div>
                </div>

                <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                    <!-- Avatar Section -->
                    <div class="relative group">
                        @if(isset($user) && $user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name ?? 'User' }}"
                                 class="profile-avatar w-48 h-48 rounded-full border-8 border-white shadow-2xl object-cover">
                        @else
                            <div class="profile-avatar w-48 h-48 rounded-full border-8 border-white shadow-2xl bg-gradient-to-br from-yellow-400 via-orange-500 to-pink-500 flex items-center justify-center text-white text-7xl font-black">
                                {{ isset($user) ? strtoupper(substr($user->name ?? 'U', 0, 1)) : 'U' }}
                            </div>
                        @endif

                        <!-- Upload Button -->
                        <label for="avatar-upload" class="absolute bottom-2 right-2 bg-gradient-to-br from-[#F46A06] to-orange-600 text-white w-16 h-16 rounded-full flex items-center justify-center cursor-pointer hover:scale-110 transition-all shadow-2xl group-hover:rotate-12">
                            <i class="fas fa-camera text-2xl"></i>
                            <input type="file" id="avatar-upload" class="hidden" accept="image/*" onchange="uploadAvatar(this)">
                        </label>

                        <!-- Status Indicator -->
                        <div class="absolute top-2 right-2 w-6 h-6 bg-green-500 border-4 border-white rounded-full shadow-lg animate-pulse"></div>
                    </div>

                    <!-- User Info -->
                    <div class="flex-1 text-center md:text-left text-white">
                        <h2 class="text-5xl font-black mb-3 drop-shadow-lg">{{ $user->name ?? 'User Name' }}</h2>

                        <div class="space-y-3 mb-4">
                            <p class="text-xl opacity-95 flex items-center justify-center md:justify-start gap-3">
                                <i class="fas fa-envelope text-2xl"></i>
                                <span>{{ $user->email ?? 'email@example.com' }}</span>
                            </p>

                            @if(isset($user) && $user->phone)
                            <p class="text-xl opacity-95 flex items-center justify-center md:justify-start gap-3">
                                <i class="fas fa-phone text-2xl"></i>
                                <span>{{ $user->phone }}</span>
                            </p>
                            @endif

                            @if(isset($user) && $user->address)
                            <p class="text-lg opacity-90 flex items-center justify-center md:justify-start gap-3">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                                <span>{{ $user->address }}</span>
                            </p>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm opacity-90">
                            <div class="bg-white/20 backdrop-blur-md px-4 py-2 rounded-full flex items-center gap-2">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Member since {{ isset($user) && $user->created_at ? $user->created_at->format('F Y') : 'N/A' }}</span>
                            </div>

                            @if(isset($user) && $user->email_verified_at)
                            <div class="bg-green-500/30 backdrop-blur-md px-4 py-2 rounded-full flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                <span>Verified Account</span>
                            </div>
                            @endif

                            <div class="bg-white/20 backdrop-blur-md px-4 py-2 rounded-full flex items-center gap-2">
                                <i class="fas fa-user-shield"></i>
                                <span>{{ isset($user) && $user->role ? ucfirst($user->role) : 'Member' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex border-b-2 border-gray-200 overflow-x-auto bg-gradient-to-r from-gray-50 to-white">
                <button class="tab-btn active flex-1 min-w-max px-8 py-5 font-bold text-[#F46A06] transition-all" onclick="switchTab(event, 'profile')">
                    <i class="fas fa-user mr-2"></i>Profile
                </button>
                <button class="tab-btn flex-1 min-w-max px-8 py-5 font-bold text-gray-500 hover:text-[#F46A06] transition-all" onclick="switchTab(event, 'security')">
                    <i class="fas fa-lock mr-2"></i>Security
                </button>
                <button class="tab-btn flex-1 min-w-max px-8 py-5 font-bold text-gray-500 hover:text-[#F46A06] transition-all" onclick="switchTab(event, 'bookings')">
                    <i class="fas fa-calendar mr-2"></i>My Bookings
                </button>
                <button class="tab-btn flex-1 min-w-max px-8 py-5 font-bold text-gray-500 hover:text-[#F46A06] transition-all" onclick="switchTab(event, 'settings')">
                    <i class="fas fa-cog mr-2"></i>Settings
                </button>
            </div>

            <!-- Profile Tab -->
            <div id="profile-tab" class="tab-content p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-3xl font-black text-gray-800 flex items-center gap-3">
                        <div class="bg-gradient-to-br from-[#F46A06] to-orange-600 p-4 rounded-2xl shadow-lg">
                            <i class="fas fa-user-edit text-white text-3xl"></i>
                        </div>
                        <span style="background: linear-gradient(135deg, #F46A06, #ff8533); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Update Profile</span>
                    </h3>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <i class="fas fa-user text-[#F46A06]"></i>
                                <span>Full Name</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                                   class="input-focus w-full border-2 border-gray-300 rounded-xl px-5 py-4 focus:ring-4 focus:ring-orange-100 transition-all font-medium">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <i class="fas fa-envelope text-[#F46A06]"></i>
                                <span>Email Address</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                                   class="input-focus w-full border-2 border-gray-300 rounded-xl px-5 py-4 focus:ring-4 focus:ring-orange-100 transition-all font-medium">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <i class="fas fa-phone text-[#F46A06]"></i>
                                <span>Phone Number</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                   placeholder="+20 XXX XXX XXX"
                                   class="input-focus w-full border-2 border-gray-300 rounded-xl px-5 py-4 focus:ring-4 focus:ring-orange-100 transition-all font-medium">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <i class="fas fa-calendar text-[#F46A06]"></i>
                                <span>Date of Birth</span>
                            </label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ?? '') }}"
                                   class="input-focus w-full border-2 border-gray-300 rounded-xl px-5 py-4 focus:ring-4 focus:ring-orange-100 transition-all font-medium">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-[#F46A06]"></i>
                            <span>Address</span>
                        </label>
                        <textarea name="address" rows="4"
                                  placeholder="Enter your full address..."
                                  class="input-focus w-full border-2 border-gray-300 rounded-xl px-5 py-4 focus:ring-4 focus:ring-orange-100 transition-all font-medium resize-none">{{ old('address', $user->address ?? '') }}</textarea>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="bg-gradient-to-r from-[#F46A06] via-orange-500 to-[#d85e05] text-white px-10 py-4 rounded-xl font-bold shadow-xl hover:shadow-2xl hover:scale-105 transition-all flex items-center gap-3 gradient-animate">
                            <i class="fas fa-save text-xl"></i>
                            <span>Save Changes</span>
                        </button>
                        <button type="reset" class="bg-gray-200 text-gray-700 px-10 py-4 rounded-xl font-bold hover:bg-gray-300 transition-all flex items-center gap-3">
                            <i class="fas fa-undo text-xl"></i>
                            <span>Reset</span>
                        </button>
                    </div>
                </form>
            </div>
            <!-- Security Tab -->
            <div id="security-tab" class="tab-content hidden p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-3xl font-black text-gray-800 flex items-center gap-3">
                        <div class="bg-gradient-to-br from-[#F46A06] to-orange-600 p-4 rounded-2xl shadow-lg">
                            <i class="fas fa-shield-halved text-white text-3xl"></i>
                        </div>
                        <span style="background: linear-gradient(135deg, #F46A06, #ff8533); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Security Settings</span>
                    </h3>
                </div>

                <!-- Change Password -->
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6 mb-8">
                    @csrf
                    @method('PUT')

                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 border-2 border-[#F46A06] rounded-2xl p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <i class="fas fa-key text-[#F46A06]"></i>
                            Change Password
                        </h4>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-lock text-[#F46A06]"></i>
                                    Current Password
                                </label>
                                <input type="password" name="current_password" required
                                       class="input-focus w-full border-2 border-gray-300 rounded-xl px-5 py-4 focus:ring-4 focus:ring-orange-100 transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-lock text-[#F46A06]"></i>
                                    New Password
                                </label>
                                <input type="password" name="password" required
                                       class="input-focus w-full border-2 border-gray-300 rounded-xl px-5 py-4 focus:ring-4 focus:ring-orange-100 transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-lock text-[#F46A06]"></i>
                                    Confirm New Password
                                </label>
                                <input type="password" name="password_confirmation" required
                                       class="input-focus w-full border-2 border-gray-300 rounded-xl px-5 py-4 focus:ring-4 focus:ring-orange-100 transition-all">
                            </div>
                        </div>

                        <button type="submit" class="mt-6 bg-gradient-to-r from-[#F46A06] to-orange-600 text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center gap-2 gradient-animate">
                            <i class="fas fa-save"></i>
                            Update Password
                        </button>
                    </div>
                </form>

                <!-- Two-Factor Authentication -->
                <div class="bg-white border-2 border-gray-200 rounded-2xl p-6 mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-mobile-alt text-[#F46A06]"></i>
                                Two-Factor Authentication
                            </h4>
                            <p class="text-gray-600">Add an extra layer of security to your account</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" {{ (isset($user) && $user->two_factor_enabled) ? 'checked' : '' }}>
                            <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#F46A06]"></div>
                        </label>
                    </div>
                </div>

                <!-- Active Sessions -->
                <div class="bg-white border-2 border-gray-200 rounded-2xl p-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-desktop text-[#F46A06]"></i>
                        Active Sessions
                    </h4>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-l-4 border-green-500">
                            <div class="flex items-center gap-4">
                                <div class="bg-green-100 p-3 rounded-xl">
                                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">Current Session</p>
                                    <p class="text-sm text-gray-600">Chrome on Windows • Cairo, Egypt</p>
                                </div>
                            </div>
                            <span class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-bold">Active</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-4">
                                <div class="bg-gray-200 p-3 rounded-xl">
                                    <i class="fas fa-mobile-alt text-gray-600 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">Mobile App</p>
                                    <p class="text-sm text-gray-600">Last active 2 hours ago</p>
                                </div>
                            </div>
                            <button class="text-red-600 hover:text-red-800 font-bold hover:scale-110 transition-all">
                                <i class="fas fa-times-circle mr-1"></i>Revoke
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings Tab -->
            <div id="bookings-tab" class="tab-content hidden p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-3xl font-black text-gray-800 flex items-center gap-3">
                        <div class="bg-gradient-to-br from-[#F46A06] to-orange-600 p-4 rounded-2xl shadow-lg">
                            <i class="fas fa-calendar-check text-white text-3xl"></i>
                        </div>
                        <span style="background: linear-gradient(135deg, #F46A06, #ff8533); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">My Bookings</span>
                    </h3>
                    <div class="flex gap-2">
                        <button class="bg-gradient-to-r from-[#F46A06] to-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:scale-105 transition-all flex items-center gap-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button class="bg-white border-2 border-[#F46A06] text-[#F46A06] px-6 py-3 rounded-xl font-bold hover:scale-105 transition-all flex items-center gap-2">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>

                @if(isset($bookings) && $bookings->count() > 0)
                    <div class="space-y-4">
                        @foreach($bookings as $booking)
                        <div class="booking-card bg-white border-2 border-gray-200 rounded-2xl p-6 shadow-lg hover:border-[#F46A06]">
                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                <div class="flex items-start gap-4 flex-1">
                                    <div class="bg-gradient-to-br from-[#F46A06] to-orange-600 p-4 rounded-2xl">
                                        <i class="fas fa-hotel text-white text-3xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $booking->facility->name ?? 'Facility Name' }}</h4>
                                        <div class="space-y-1 text-gray-600">
                                            <p class="flex items-center gap-2">
                                                <i class="fas fa-calendar text-[#F46A06]"></i>
                                                <span>{{ $booking->check_in ?? 'N/A' }} - {{ $booking->check_out ?? 'N/A' }}</span>
                                            </p>
                                            <p class="flex items-center gap-2">
                                                <i class="fas fa-users text-[#F46A06]"></i>
                                                <span>{{ $booking->guests ?? 1 }} Guests</span>
                                            </p>
                                            <p class="flex items-center gap-2">
                                                <i class="fas fa-dollar-sign text-[#F46A06]"></i>
                                                <span class="font-bold">${{ number_format($booking->total_price ?? 0, 2) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-3">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-500',
                                            'confirmed' => 'bg-blue-100 text-blue-800 border-blue-500',
                                            'completed' => 'bg-green-100 text-green-800 border-green-500',
                                            'cancelled' => 'bg-red-100 text-red-800 border-red-500'
                                        ];
                                        $statusColor = $statusColors[$booking->status ?? 'pending'] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-4 py-2 rounded-full text-sm font-bold border-2 {{ $statusColor }}">
                                        {{ ucfirst($booking->status ?? 'Pending') }}
                                    </span>

                                    <div class="flex gap-2">
                                        <button class="bg-[#F46A06] text-white px-4 py-2 rounded-lg font-bold hover:bg-orange-600 transition-all flex items-center gap-2">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        @if(isset($booking->status) && $booking->status === 'pending')
                                        <button class="bg-red-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-600 transition-all flex items-center gap-2">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="inline-block p-8 bg-gradient-to-br from-orange-100 to-orange-200 rounded-full mb-6">
                            <i class="fas fa-calendar-times text-[#F46A06] text-6xl"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-800 mb-3">No Bookings Yet</h4>
                        <p class="text-gray-600 mb-6">You haven't made any bookings yet. Start exploring!</p>
                        <a href="{{ route('home') }}" class="inline-flex items-center bg-gradient-to-r from-[#F46A06] to-orange-600 text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all gap-2 gradient-animate">
                            <i class="fas fa-search"></i>
                            Browse Facilities
                        </a>
                    </div>
                @endif
            </div>

            <!-- Settings Tab -->
            <div id="settings-tab" class="tab-content hidden p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-3xl font-black text-gray-800 flex items-center gap-3">
                        <div class="bg-gradient-to-br from-[#F46A06] to-orange-600 p-4 rounded-2xl shadow-lg">
                            <i class="fas fa-sliders-h text-white text-3xl"></i>
                        </div>
                        <span style="background: linear-gradient(135deg, #F46A06, #ff8533); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Preferences & Settings</span>
                    </h3>
                </div>

                <div class="space-y-6">
                    <!-- Notification Settings -->
                    <div class="bg-white border-2 border-gray-200 rounded-2xl p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <i class="fas fa-bell text-[#F46A06]"></i>
                            Notification Preferences
                        </h4>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <p class="font-bold text-gray-800">Email Notifications</p>
                                    <p class="text-sm text-gray-600">Receive booking updates via email</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#F46A06]"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <p class="font-bold text-gray-800">SMS Notifications</p>
                                    <p class="text-sm text-gray-600">Get text messages for important updates</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#F46A06]"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <p class="font-bold text-gray-800">Push Notifications</p>
                                    <p class="text-sm text-gray-600">Enable browser push notifications</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#F46A06]"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Settings -->
                    <div class="bg-white border-2 border-gray-200 rounded-2xl p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <i class="fas fa-user-secret text-[#F46A06]"></i>
                            Privacy Settings
                        </h4>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <p class="font-bold text-gray-800">Profile Visibility</p>
                                    <p class="text-sm text-gray-600">Make your profile visible to others</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#F46A06]"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <p class="font-bold text-gray-800">Show Online Status</p>
                                    <p class="text-sm text-gray-600">Let others see when you're online</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#F46A06]"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-500 rounded-2xl p-6">
                        <h4 class="text-xl font-bold text-red-800 mb-6 flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            Danger Zone
                        </h4>

                        <div class="space-y-4">
                            <button class="w-full bg-white border-2 border-red-500 text-red-600 px-6 py-4 rounded-xl font-bold hover:bg-red-50 transition-all flex items-center justify-between">
                                <span>Deactivate Account</span>
                                <i class="fas fa-user-slash"></i>
                            </button>

                            <button onclick="confirmDeleteAccount()" class="w-full bg-red-500 text-white px-6 py-4 rounded-xl font-bold hover:bg-red-600 transition-all flex items-center justify-between">
                                <span>Delete Account Permanently</span>
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Footer -->
<!-- Footer -->
<footer class="bg-[#211C24] text-white pt-16 pb-8 mt-12">
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
                    Your trusted platform for discovering the best hospitality facilities near you.
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
                    <li><a href="{{ url('/') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors flex items-center gap-2 group">
                        <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        Home
                    </a></li>
                    <li><a href="#" class="text-gray-400 hover:text-[#F46A06] transition-colors flex items-center gap-2 group">
                        <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        About Us
                    </a></li>
                    <li><a href="#" class="text-gray-400 hover:text-[#F46A06] transition-colors flex items-center gap-2 group">
                        <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        Services
                    </a></li>
                    <li><a href="{{ url('/contact') }}" class="text-gray-400 hover:text-[#F46A06] transition-colors flex items-center gap-2 group">
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
                <p class="text-gray-400 mb-4">Subscribe to get updates</p>
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

    // Tab Switching
    function switchTab(event, tabName) {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'text-[#F46A06]');
            btn.classList.add('text-gray-500');
        });

        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        document.getElementById(tabName + '-tab').classList.remove('hidden');

        event.currentTarget.classList.add('active', 'text-[#F46A06]');
        event.currentTarget.classList.remove('text-gray-500');
    }

    // Avatar Upload
    function uploadAvatar(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            if (file.size > 5 * 1024 * 1024) {
                showNotification('File size must be less than 5MB', 'error');
                return;
            }

            if (!file.type.startsWith('image/')) {
                showNotification('Please select an image file', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('avatar', file);
            formData.append('_token', '{{ csrf_token() }}');

            showNotification('Uploading avatar...', 'info');

            fetch('{{ route("profile.avatar.update") }}', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Avatar updated successfully!', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Upload failed', 'error');
                }
            })
            .catch(error => {
                showNotification('An error occurred', 'error');
            });
        }
    }

    // Download Profile
    function downloadProfile() {
        showNotification('Preparing your data...', 'info');

        setTimeout(() => {
            const data = {
                name: '{{ $user->name ?? "User" }}',
                email: '{{ $user->email ?? "N/A" }}',
                phone: '{{ $user->phone ?? "N/A" }}',
                member_since: '{{ isset($user) && $user->created_at ? $user->created_at->format("F Y") : "N/A" }}'
            };

            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'profile-data.json';
            a.click();

            showNotification('Profile data downloaded!', 'success');
        }, 1000);
    }

    // Confirm Delete Account
    function confirmDeleteAccount() {
        if (confirm('Are you sure you want to delete your account? This action cannot be undone!')) {
            if (confirm('Please confirm again. All your data will be permanently deleted!')) {
                showNotification('Deleting account...', 'info');

                // Add actual delete logic here
                setTimeout(() => {
                    showNotification('Account deleted successfully', 'success');
                }, 2000);
            }
        }
    }

    // Show Notification
    function showNotification(message, type = 'success') {
        const colors = {
            success: 'from-green-500 to-emerald-500',
            error: 'from-red-500 to-pink-500',
            info: 'from-blue-500 to-cyan-500'
        };

        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };

        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 animate-slide';
        notification.innerHTML = `
            <div class="bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 min-w-[300px]">
                <i class="fas ${icons[type]} text-2xl"></i>
                <span class="font-bold">${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(400px)';
            notification.style.transition = 'all 0.5s ease';
            setTimeout(() => notification.remove(), 500);
        }, 3000);
    }

    // Auto-dismiss messages
    setTimeout(() => {
        document.querySelectorAll('[data-aos]').forEach(el => {
            if (el.querySelector('.fas.fa-times')) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-20px)';
                setTimeout(() => el.remove(), 500);
            }
        });
    }, 5000);
</script>
@endpush
