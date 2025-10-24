<!DOCTYPE html>
<html lang="en" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true',
    sidebarOpen: false,
    showNotifications: false,
    showProfile: false,
    notifications: [
        { id: 1, type: 'success', icon: 'fa-check-circle', title: 'New Booking', message: 'Room #205 booked successfully', time: '2 mins ago', unread: true },
        { id: 2, type: 'warning', icon: 'fa-exclamation-triangle', title: 'Payment Pending', message: 'Invoice #1234 awaiting payment', time: '15 mins ago', unread: true },
        { id: 3, type: 'info', icon: 'fa-info-circle', title: 'System Update', message: 'New features available', time: '1 hour ago', unread: true },
        { id: 4, type: 'error', icon: 'fa-times-circle', title: 'Booking Cancelled', message: 'Guest cancelled reservation', time: '2 hours ago', unread: false }
    ]
}"
x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
:class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - Hospitality Management')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #F46A06;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #d85e05;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #F46A06;
        }

        .dark ::-webkit-scrollbar-track {
            background: #1f2937;
        }

        /* Navigation Link Effects */
        .nav-link {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(244, 106, 6, 0.15), rgba(244, 106, 6, 0.05));
            border-left: 4px solid #F46A06;
            padding-left: calc(1rem - 4px);
        }

        .nav-link:hover:not(.active) {
            background: rgba(244, 106, 6, 0.08);
            transform: translateX(5px);
        }

        .dark .nav-link.active {
            background: linear-gradient(135deg, rgba(244, 106, 6, 0.25), rgba(244, 106, 6, 0.1));
        }

        .dark .nav-link:hover:not(.active) {
            background: rgba(244, 106, 6, 0.15);
        }

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass {
            background: rgba(31, 41, 55, 0.95);
            border: 1px solid rgba(75, 85, 99, 0.3);
        }

        /* Gradient Background */
        .gradient-bg {
            background: linear-gradient(-45deg, #F46A06, #ff8c42, #ff6b35, #F46A06);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Dark Mode Gradient */
        .dark .gradient-bg {
            background: linear-gradient(-45deg, #1f2937, #374151, #4b5563, #1f2937);
            background-size: 400% 400%;
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        /* Card Hover */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(244, 106, 6, 0.2);
        }

        .dark .card-hover:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        /* Notification Badge Pulse */
        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }

        /* Page Transition */
        .page-transition {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Progress Bar */
        .progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #F46A06, #ff8c42);
            width: 0%;
            z-index: 9999;
            transition: width 0.3s ease;
            box-shadow: 0 0 10px rgba(244, 106, 6, 0.5);
        }

        /* Mobile Sidebar */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                height: 100vh;
                z-index: 50;
                transition: left 0.3s ease;
            }

            .sidebar.open {
                left: 0;
            }
        }

        /* Dark Mode Toggle Animation */
        .dark-mode-toggle {
            transition: transform 0.3s ease;
        }

        .dark-mode-toggle:hover {
            transform: rotate(180deg);
        }

        /* Smooth transitions for dark mode */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        /* Notification Panel */
        .notification-panel {
            max-height: 500px;
            overflow-y: auto;
        }

        /* Toast Notification */
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast-enter {
            animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .toast-leave {
            animation: slideOutRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Dropdown Animation */
        .dropdown-enter {
            animation: dropdownIn 0.2s ease-out;
        }

        @keyframes dropdownIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Notification Dot Animation */
        @keyframes ping {
            75%, 100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        .animate-ping {
            animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        /* Profile Dropdown */
        .profile-dropdown {
            min-width: 280px;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 min-h-screen transition-colors duration-300">

    <!-- Progress Bar -->
    <div class="progress-bar" id="progress-bar"></div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-20 right-4 z-[100] space-y-3 pointer-events-none">
        <!-- Toasts will be inserted here -->
    </div>

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 md:hidden"></div>

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside :class="{ 'open': sidebarOpen }"
               class="sidebar w-72 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col shadow-2xl transition-all duration-300">

            <!-- Logo Section -->
            <div class="p-6 text-center border-b border-gray-200 dark:border-gray-700">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#F46A06] to-[#ff8c42] text-white px-6 py-3 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 cursor-pointer float-animation">
                    <i class="fas fa-hotel text-2xl"></i>
                    <span class="text-2xl font-bold">HFS</span>
                </a>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Hospitality Finder System</p>
            </div>

            <!-- User Profile -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700" x-data="{ showMenu: false }">
                <div @click="showMenu = !showMenu" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition cursor-pointer">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#F46A06] to-[#ff8c42] flex items-center justify-center text-white font-bold text-xl shadow-lg ring-2 ring-[#F46A06]/20">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-sm text-gray-800 dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(Auth::user()->role ?? 'Admin') }}</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 transition-transform" :class="{ 'rotate-90': showMenu }"></i>
                </div>

                <!-- Quick Actions -->
                <div x-show="showMenu"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="mt-2 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                        <i class="fas fa-user-edit w-4"></i>
                        <span>Edit Profile</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                        <i class="fas fa-cog w-4"></i>
                        <span>Settings</span>
                    </a>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-4 mb-3">Main Menu</p>

                <a href="{{ route('dashboard.hotel') }}"
                   class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 {{ request()->routeIs('dashboard.hotel*') ? 'active' : '' }}">
                    <i class="fas fa-hotel w-5 text-[#F46A06]"></i>
                    <span class="font-medium">Hotels</span>
                    <span class="ml-auto bg-[#F46A06]/10 dark:bg-[#F46A06]/20 text-[#F46A06] text-xs px-2 py-1 rounded-full font-semibold">12</span>
                </a>

                <a href="{{ route('dashboard.restaurant') }}"
                   class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 {{ request()->routeIs('dashboard.restaurant*') ? 'active' : '' }}">
                    <i class="fas fa-utensils w-5 text-[#F46A06]"></i>
                    <span class="font-medium">Restaurants</span>
                    <span class="ml-auto bg-[#F46A06]/10 dark:bg-[#F46A06]/20 text-[#F46A06] text-xs px-2 py-1 rounded-full font-semibold">8</span>
                </a>

                <a href="{{ route('dashboard.coffee') }}"
                   class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 {{ request()->routeIs('dashboard.coffee*') ? 'active' : '' }}">
                    <i class="fas fa-mug-hot w-5 text-[#F46A06]"></i>
                    <span class="font-medium">Coffee Shops</span>
                    <span class="ml-auto bg-[#F46A06]/10 dark:bg-[#F46A06]/20 text-[#F46A06] text-xs px-2 py-1 rounded-full font-semibold">5</span>
                </a>

                <a href="{{ route('dashboard.bookings.index') }}"
                   class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 {{ request()->routeIs('dashboard.bookings.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check w-5 text-[#F46A06]"></i>
                    <span class="font-medium">Bookings</span>
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full notification-badge font-semibold">3</span>
                </a>

                <a href="{{ route('dashboard.payment') }}"
                   class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 {{ request()->routeIs('dashboard.payment*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card w-5 text-[#F46A06]"></i>
                    <span class="font-medium">Payments</span>
                </a>

                <a href="{{ route('dashboard.transport') }}"
                   class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 {{ request()->routeIs('dashboard.transport*') ? 'active' : '' }}">
                    <i class="fas fa-car w-5 text-[#F46A06]"></i>
                    <span class="font-medium">Transportation</span>
                </a>

                <div class="border-t border-gray-200 dark:border-gray-700 my-3"></div>

                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-4 mb-3">Settings</p>

                <a href="{{ route('profile.edit') }}"
                   class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300">
                    <i class="fas fa-user w-5 text-[#F46A06]"></i>
                    <span class="font-medium">Profile</span>
                </a>

                <button @click="darkMode = !darkMode"
                   class="nav-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300">
                    <i class="w-5 text-[#F46A06] dark-mode-toggle" :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'"></i>
                    <span class="font-medium" x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                </button>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 font-semibold py-3 px-4 rounded-xl flex items-center justify-center gap-3 transition-all duration-300 hover:shadow-lg">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0">

            <!-- Header -->
            <header class="glass sticky top-0 z-30 shadow-lg">
                <div class="flex justify-between items-center px-6 py-4">
                    <!-- Left Section -->
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-600 dark:text-gray-300 hover:text-[#F46A06] transition">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>

                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">@yield('header', 'Dashboard')</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Welcome back, {{ Auth::user()->name }}!</p>
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center gap-4">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <input type="text" placeholder="Search anything... (Ctrl+K)"
                                class="w-64 bg-gray-100 dark:bg-gray-700 border-0 rounded-xl px-4 py-2.5 pl-10 text-gray-800 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F46A06] transition">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- Notifications -->
                        <div class="relative" @click.away="showNotifications = false">
                            <button @click="showNotifications = !showNotifications" class="relative p-2.5 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                                <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full animate-ping"></span>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full font-bold" x-text="notifications.filter(n => n.unread).length"></span>
                            </button>

                            <!-- Notifications Dropdown -->
                            <div x-show="showNotifications"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden dropdown-enter">

                                <!-- Header -->
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-[#F46A06]/5 to-transparent">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-bold text-lg text-gray-800 dark:text-white">Notifications</h3>
                                        <button @click="notifications.forEach(n => n.unread = false)" class="text-sm text-[#F46A06] hover:text-[#d85e05] font-semibold">
                                            Mark all as read
                                        </button>
                                    </div>
                                </div>

                                <!-- Notifications List -->
                                <div class="notification-panel">
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div @click="notification.unread = false"
                                             class="p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition"
                                             :class="{ 'bg-[#F46A06]/5': notification.unread }">
                                            <div class="flex gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                                         :class="{
                                                             'bg-green-100 dark:bg-green-900/30 text-green-600': notification.type === 'success',
                                                             'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600': notification.type === 'warning',
                                                             'bg-blue-100 dark:bg-blue-900/30 text-blue-600': notification.type === 'info',
                                                             'bg-red-100 dark:bg-red-900/30 text-red-600': notification.type === 'error'
                                                         }">
                                                        <i :class="'fas ' + notification.icon"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex justify-between items-start mb-1">
                                                        <h4 class="font-semibold text-sm text-gray-800 dark:text-white" x-text="notification.title"></h4>
                                                        <span x-show="notification.unread" class="w-2 h-2 bg-[#F46A06] rounded-full"></span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1" x-text="notification.message"></p>
                                                    <span class="text-xs text-gray-500 dark:text-gray-500" x-text="notification.time"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Footer -->
                                <div class="p-3 text-center border-t border-gray-200 dark:border-gray-700">
                                    <a href="#" class="text-sm text-[#F46A06] hover:text-[#d85e05] font-semibold">View all notifications</a>
                                </div>
                            </div>
                        </div>

                        <!-- Dark Mode Toggle (Desktop) -->
                        <button @click="darkMode = !darkMode" class="hidden md:block p-2.5 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition dark-mode-toggle">
                            <i class="text-xl" :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'"></i>
                        </button>

                        <!-- Profile Menu -->
                        <div class="relative" @click.away="showProfile = false">
                            <button @click="showProfile = !showProfile" class="flex items-center gap-2 p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#F46A06] to-[#ff8c42] flex items-center justify-center text-white font-bold shadow-lg ring-2 ring-[#F46A06]/20">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" :class="{ 'rotate-180': showProfile }"></i>
                            </button>

                            <!-- Profile Dropdown -->
                            <div x-show="showProfile"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="absolute right-0 mt-2 profile-dropdown bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden dropdown-enter">

                                <!-- Profile Info -->
                                <div class="p-4 bg-gradient-to-r from-[#F46A06]/10 to-transparent border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#F46A06] to-[#ff8c42] flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 dark:text-white">{{ Auth::user()->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-2">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <i class="fas fa-user-circle w-5 text-[#F46A06]"></i>
                                        <span class="font-medium">My Profile</span>
                                    </a>
                                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <i class="fas fa-cog w-5 text-[#F46A06]"></i>
                                        <span class="font-medium">Settings</span>
                                    </a>
                                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <i class="fas fa-question-circle w-5 text-[#F46A06]"></i>
                                        <span class="font-medium">Help & Support</span>
                                    </a>
                                </div>

                                <!-- Logout -->
                                <div class="p-3 border-t border-gray-200 dark:border-gray-700">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition font-medium">
                                            <i class="fas fa-sign-out-alt w-5"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <section class="p-6 flex-1 overflow-y-auto page-transition">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="mb-6 bg-gradient-to-r from-green-50 to-green-100/50 dark:from-green-900/20 dark:to-green-900/10 border-l-4 border-green-500 text-green-700 dark:text-green-400 px-6 py-4 rounded-r-xl flex items-center justify-between shadow-lg backdrop-blur-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-check text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="font-bold text-sm mb-1">Success!</p>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-green-700 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 transition">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100/50 dark:from-red-900/20 dark:to-red-900/10 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-6 py-4 rounded-r-xl flex items-center justify-between shadow-lg backdrop-blur-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="font-bold text-sm mb-1">Error!</p>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-red-700 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200 transition">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6 bg-gradient-to-r from-yellow-50 to-yellow-100/50 dark:from-yellow-900/20 dark:to-yellow-900/10 border-l-4 border-yellow-500 text-yellow-700 dark:text-yellow-400 px-6 py-4 rounded-r-xl flex items-center justify-between shadow-lg backdrop-blur-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-exclamation-circle text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="font-bold text-sm mb-1">Warning!</p>
                                <span class="font-medium">{{ session('warning') }}</span>
                            </div>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-yellow-700 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-200 transition">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-6 bg-gradient-to-r from-blue-50 to-blue-100/50 dark:from-blue-900/20 dark:to-blue-900/10 border-l-4 border-blue-500 text-blue-700 dark:text-blue-400 px-6 py-4 rounded-r-xl flex items-center justify-between shadow-lg backdrop-blur-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-info-circle text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="font-bold text-sm mb-1">Info!</p>
                                <span class="font-medium">{{ session('info') }}</span>
                            </div>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 transition">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </section>

            <!-- Footer -->
            <footer class="glass px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                    <p>© 2025 Hospitality Finder System. All rights reserved.</p>
                    <div class="flex gap-6">
                        <a href="#" class="hover:text-[#F46A06] transition">Privacy Policy</a>
                        <a href="#" class="hover:text-[#F46A06] transition">Terms of Service</a>
                        <a href="#" class="hover:text-[#F46A06] transition">Support</a>
                    </div>
                </div>
            </footer>

        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Progress bar
        let progressBar = document.getElementById('progress-bar');

        window.addEventListener('beforeunload', () => {
            progressBar.style.width = '100%';
        });

        document.addEventListener('DOMContentLoaded', () => {
            progressBar.style.width = '0%';
        });

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                progressBar.style.width = '70%';
            });
        });

        // Auto-hide alerts after 5 seconds with smooth animation
        setTimeout(() => {
            document.querySelectorAll('[class*="from-green-"], [class*="from-red-"], [class*="from-yellow-"], [class*="from-blue-"]').forEach(el => {
                if (el.querySelector('button[onclick]')) {
                    el.style.transition = 'all 0.5s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'translateX(100%)';
                    setTimeout(() => el.remove(), 500);
                }
            });
        }, 5000);

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.querySelector('input[placeholder*="Search"]')?.focus();
            }

            // Ctrl/Cmd + N for notifications
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                document.querySelector('[x-data] button i.fa-bell')?.parentElement.click();
            }
        });

        // Advanced Toast System
        window.showToast = function(message, type = 'success', duration = 4000) {
            const toastContainer = document.getElementById('toast-container');

            const icons = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };

            const colors = {
                success: 'from-green-500 to-green-600',
                error: 'from-red-500 to-red-600',
                warning: 'from-yellow-500 to-yellow-600',
                info: 'from-blue-500 to-blue-600'
            };

            const toast = document.createElement('div');
            toast.className = `pointer-events-auto bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-2xl shadow-2xl transform transition-all duration-400 toast-enter flex items-center gap-4 min-w-[320px] max-w-md`;
            toast.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas ${icons[type]} text-xl"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-sm mb-1">${type.charAt(0).toUpperCase() + type.slice(1)}!</p>
                    <p class="text-sm opacity-90">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="flex-shrink-0 hover:bg-white/20 rounded-lg p-2 transition">
                    <i class="fas fa-times"></i>
                </button>
            `;

            toastContainer.appendChild(toast);

            // Play sound effect
            try {
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSl+zPLTgjMGHm7A7+OZVA0PVaro7bNpHwU9k9rx0IA');
                audio.volume = 0.3;
                audio.play().catch(() => {});
            } catch(e) {}

            setTimeout(() => {
                toast.classList.add('toast-leave');
                setTimeout(() => toast.remove(), 400);
            }, duration);
        };

        // Enhanced notification system
        window.showNotification = function(title, message, type = 'info') {
            const notification = {
                id: Date.now(),
                type: type,
                icon: {
                    success: 'fa-check-circle',
                    error: 'fa-times-circle',
                    warning: 'fa-exclamation-triangle',
                    info: 'fa-info-circle'
                }[type],
                title: title,
                message: message,
                time: 'Just now',
                unread: true
            };

            // Add to Alpine.js notifications
            const event = new CustomEvent('add-notification', { detail: notification });
            window.dispatchEvent(event);

            // Show toast
            showToast(message, type);
        };

        // Example usage on page load
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif

        @if(session('warning'))
            showToast('{{ session('warning') }}', 'warning');
        @endif

        @if(session('info'))
            showToast('{{ session('info') }}', 'info');
        @endif

        // Simulate real-time notifications (for demo)
        function simulateNotification() {
            const notifications = [
                { title: 'New Booking', message: 'Room #305 has been booked', type: 'success' },
                { title: 'Payment Received', message: 'Payment of $250 received', type: 'success' },
                { title: 'Low Stock Alert', message: 'Coffee beans running low', type: 'warning' },
                { title: 'System Update', message: 'New features are available', type: 'info' },
                { title: 'Booking Cancelled', message: 'Reservation #1234 cancelled', type: 'error' }
            ];

            const random = notifications[Math.floor(Math.random() * notifications.length)];
            showNotification(random.title, random.message, random.type);
        }

        // Uncomment to test notifications every 30 seconds
        // setInterval(simulateNotification, 30000);

        // Export to CSV
        window.exportToCSV = function(tableId, filename = 'export.csv') {
            const table = document.getElementById(tableId);
            if (!table) {
                showToast('Table not found!', 'error');
                return;
            }

            let csv = [];
            const rows = table.querySelectorAll('tr');

            rows.forEach(row => {
                const cols = row.querySelectorAll('td, th');
                const rowData = Array.from(cols).map(col => {
                    return '"' + col.textContent.trim().replace(/"/g, '""') + '"';
                });
                csv.push(rowData.join(','));
            });

            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            link.click();
            window.URL.revokeObjectURL(url);

            showToast('Data exported successfully!', 'success');
        };

        // Print function
        window.printSection = function(elementId) {
            const element = document.getElementById(elementId);
            if (!element) {
                showToast('Element not found!', 'error');
                return;
            }

            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print</title>');
            printWindow.document.write('<style>body { font-family: Arial, sans-serif; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(element.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        };

        // Copy to clipboard
        window.copyToClipboard = function(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Copied to clipboard!', 'success');
            }).catch(() => {
                showToast('Failed to copy!', 'error');
            });
        };

        // Confirm delete with sweet alert style
        window.confirmDelete = function(message = 'Are you sure you want to delete this item?') {
            return confirm(message);
        };

        // Loading overlay
        window.showLoading = function() {
            const overlay = document.createElement('div');
            overlay.id = 'loading-overlay';
            overlay.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center';
            overlay.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-2xl flex flex-col items-center gap-4">
                    <div class="w-16 h-16 border-4 border-[#F46A06] border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-gray-800 dark:text-white font-semibold">Loading...</p>
                </div>
            `;
            document.body.appendChild(overlay);
        };

        window.hideLoading = function() {
            const overlay = document.getElementById('loading-overlay');
            if (overlay) overlay.remove();
        };

        // Initialize tooltips
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            element.addEventListener('mouseenter', function() {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip';
                tooltip.textContent = this.dataset.tooltip;
                document.body.appendChild(tooltip);
            });
        });

        console.log('%c🚀 HFS Dashboard Loaded Successfully!', 'color: #F46A06; font-size: 18px; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);');
        console.log('%c✨ All features activated!', 'color: #10b981; font-size: 14px;');
    </script>

    @stack('scripts')
</body>
</html>
