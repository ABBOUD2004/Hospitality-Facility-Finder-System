@extends('layouts.dashboard')

@section('title', 'Payment Management')
@section('header', 'Payment Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#F46A06] to-orange-600 dark:from-[#d85e05] dark:to-orange-700 rounded-2xl shadow-xl p-6 text-white">
        <h1 class="text-4xl font-bold mb-2 flex items-center gap-3">
            <i class="fas fa-dollar-sign text-5xl"></i>
            Payment Management
        </h1>
        <p class="text-orange-100 dark:text-orange-200 text-lg">Monitor and manage all payments and transactions</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 p-4 rounded-lg shadow-lg animate-pulse">
            <p class="text-green-700 dark:text-green-300 font-semibold flex items-center gap-2">
                <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
            </p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 p-4 rounded-lg shadow-lg">
            <p class="text-red-700 dark:text-red-300 font-semibold flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-xl"></i> {{ session('error') }}
            </p>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Total Revenue</h3>
                <i class="fas fa-chart-line text-3xl opacity-30"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
            <p class="text-sm opacity-75 mt-1">+{{ $stats['growth_percentage'] ?? 0 }}% from last month</p>
        </div>

        <!-- Pending Payments -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 dark:from-yellow-600 dark:to-yellow-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Pending</h3>
                <i class="fas fa-clock text-3xl opacity-30"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($stats['pending_amount'] ?? 0, 2) }}</p>
            <p class="text-sm opacity-75 mt-1">{{ $stats['pending_count'] ?? 0 }} transactions</p>
        </div>

        <!-- Completed Payments -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Completed</h3>
                <i class="fas fa-check-circle text-3xl opacity-30"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($stats['completed_amount'] ?? 0, 2) }}</p>
            <p class="text-sm opacity-75 mt-1">{{ $stats['completed_count'] ?? 0 }} transactions</p>
        </div>

        <!-- Failed Payments -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 dark:from-red-600 dark:to-red-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Failed</h3>
                <i class="fas fa-times-circle text-3xl opacity-30"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($stats['failed_amount'] ?? 0, 2) }}</p>
            <p class="text-sm opacity-75 mt-1">{{ $stats['failed_count'] ?? 0 }} transactions</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
            <i class="fas fa-filter text-[#F46A06]"></i>
            Filter Payments
        </h2>

        <form method="GET" action="{{ route('dashboard.payment') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Booking Type</label>
                    <select name="booking_type" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition">
                        <option value="">All Types</option>
                        <option value="hotel" {{ request('booking_type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                        <option value="restaurant" {{ request('booking_type') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                        <option value="coffee" {{ request('booking_type') == 'coffee' ? 'selected' : '' }}>Coffee Shop</option>
                        <option value="transport" {{ request('booking_type') == 'transport' ? 'selected' : '' }}>Transport</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#F46A06] dark:focus:ring-[#ff8c42] transition">
                </div>
            </div>

            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-[#F46A06] hover:bg-orange-600 dark:bg-[#ff8c42] dark:hover:bg-[#F46A06] text-white px-6 py-2 rounded-lg transition font-semibold flex items-center gap-2 shadow-lg">
                    <i class="fas fa-search"></i> Apply Filters
                </button>
                <a href="{{ route('dashboard.payment') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg transition font-semibold flex items-center gap-2">
                    <i class="fas fa-times"></i> Clear
                </a>
                <a href="{{ route('dashboard.payments.export', request()->all()) }}" class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-6 py-2 rounded-lg transition font-semibold flex items-center gap-2 ml-auto shadow-lg">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="bg-gradient-to-r from-[#F46A06] to-orange-600 dark:from-[#d85e05] dark:to-orange-700 text-white p-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i class="fas fa-list"></i>
                Payment Transactions
            </h2>
            <button onclick="location.reload()" class="bg-white/20 hover:bg-white/30 dark:bg-white/10 dark:hover:bg-white/20 px-4 py-2 rounded-lg transition">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-900 border-b-2 border-gray-300 dark:border-gray-600">
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">ID</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">Customer</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">Contact</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">Booking Type</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">Amount</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">Payment Method</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 dark:text-gray-300">Date</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700 dark:text-gray-300">Status</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-orange-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">#PAY-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800 dark:text-white">{{ $payment->guest_name ?? $payment->user->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $payment->guest_email ?? $payment->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $payment->guest_phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $typeColors = [
                                        'hotel' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'restaurant' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                        'coffee' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                        'transport' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    ];
                                    $typeIcons = [
                                        'hotel' => 'fa-hotel',
                                        'restaurant' => 'fa-utensils',
                                        'coffee' => 'fa-coffee',
                                        'transport' => 'fa-car',
                                    ];
                                @endphp
                                <span class="{{ $typeColors[$payment->booking_type] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }} px-3 py-1 rounded-full text-sm font-bold inline-flex items-center gap-1">
                                    <i class="fas {{ $typeIcons[$payment->booking_type] ?? 'fa-circle' }}"></i>
                                    {{ ucfirst($payment->booking_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold
                                @if($payment->payment_status == 'paid') text-green-600 dark:text-green-400
                                @elseif($payment->payment_status == 'pending') text-yellow-600 dark:text-yellow-400
                                @elseif($payment->payment_status == 'failed') text-red-600 dark:text-red-400
                                @endif">
                                ${{ number_format($payment->total_price, 2) }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <i class="fas fa-credit-card"></i>
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-sm">
                                {{ $payment->created_at->format('M d, Y') }}<br>
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $payment->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'failed' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        'refunded' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    ];
                                    $statusIcons = [
                                        'paid' => 'fa-check',
                                        'pending' => 'fa-clock',
                                        'failed' => 'fa-times',
                                        'refunded' => 'fa-undo',
                                    ];
                                @endphp
                                <span class="{{ $statusColors[$payment->payment_status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }} px-4 py-2 rounded-lg font-bold inline-flex items-center gap-1">
                                    <i class="fas {{ $statusIcons[$payment->payment_status] ?? 'fa-circle' }}"></i>
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('dashboard.payments.show', $payment->id) }}" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white p-2 rounded-lg transition" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($payment->payment_status == 'pending')
                                        <form action="{{ route('dashboard.payments.confirm', $payment->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white p-2 rounded-lg transition" title="Confirm Payment">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($payment->payment_status == 'failed')
                                        <form action="{{ route('dashboard.payments.retry', $payment->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white p-2 rounded-lg transition" title="Retry Payment">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($payment->payment_status == 'paid')
                                        <a href="{{ route('dashboard.payments.invoice', $payment->id) }}" class="bg-purple-500 hover:bg-purple-600 dark:bg-purple-600 dark:hover:bg-purple-700 text-white p-2 rounded-lg transition" title="Download Invoice">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <i class="fas fa-inbox text-6xl text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-xl font-semibold text-gray-500 dark:text-gray-400">No payments found</p>
                                    <p class="text-gray-400 dark:text-gray-500">Try adjusting your filters or create a new payment</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
            <div class="text-gray-600 dark:text-gray-400">
                @if($payments->total() > 0)
                    Showing <span class="font-semibold">{{ $payments->firstItem() }}</span> to
                    <span class="font-semibold">{{ $payments->lastItem() }}</span> of
                    <span class="font-semibold">{{ $payments->total() }}</span> transactions
                @else
                    No transactions found
                @endif
            </div>
            <div>
                {{ $payments->links() }}
            </div>
        </div>
    </div>

    <!-- Payment Methods Chart & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payment Methods Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-[#F46A06]"></i>
                Payment Methods
            </h3>
            <div class="space-y-4">
                @foreach($paymentMethods as $method)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-{{ $method['color'] }}-500 dark:bg-{{ $method['color'] }}-400 rounded"></div>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $method['name'] }}</span>
                            </div>
                            <span class="font-bold text-gray-800 dark:text-white">{{ $method['percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                            <div class="bg-{{ $method['color'] }}-500 dark:bg-{{ $method['color'] }}-400 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $method['percentage'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-history text-[#F46A06]"></i>
                Recent Activity
            </h3>
            <div class="space-y-4">
                @forelse($recentActivity as $activity)
                    @php
                        $config = [
                            'paid' => ['bg' => 'green', 'icon' => 'fa-check', 'text' => 'Payment Received'],
                            'pending' => ['bg' => 'yellow', 'icon' => 'fa-clock', 'text' => 'Pending Payment'],
                            'failed' => ['bg' => 'red', 'icon' => 'fa-times', 'text' => 'Payment Failed'],
                            'refunded' => ['bg' => 'gray', 'icon' => 'fa-undo', 'text' => 'Refund Processed'],
                        ][$activity->payment_status] ?? ['bg' => 'gray', 'icon' => 'fa-circle', 'text' => 'Payment Activity'];
                    @endphp
                    <div class="flex items-start gap-3 p-3 bg-{{ $config['bg'] }}-50 dark:bg-{{ $config['bg'] }}-900/20 rounded-lg border border-{{ $config['bg'] }}-100 dark:border-{{ $config['bg'] }}-800">
                        <div class="bg-{{ $config['bg'] }}-500 dark:bg-{{ $config['bg'] }}-600 text-white p-2 rounded-full flex-shrink-0">
                            <i class="fas {{ $config['icon'] }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 dark:text-white">{{ $config['text'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $activity->guest_name ?? $activity->user->name ?? 'N/A' }} • ${{ number_format($activity->total_price, 2) }} • {{ ucfirst($activity->booking_type) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 dark:text-gray-500 py-8">No recent activity</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <button onclick="alert('Manual Payment feature coming soon!')" class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 text-white p-6 rounded-2xl shadow-xl hover:scale-105 transition-transform flex items-center gap-4">
            <i class="fas fa-plus-circle text-4xl"></i>
            <div class="text-left">
                <h4 class="font-bold text-lg">Manual Payment</h4>
                <p class="text-sm opacity-80">Record a payment</p>
            </div>
        </button>

        <a href="{{ route('dashboard.payments.export', request()->all()) }}" class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 text-white p-6 rounded-2xl shadow-xl hover:scale-105 transition-transform flex items-center gap-4">
            <i class="fas fa-file-export text-4xl"></i>
            <div class="text-left">
                <h4 class="font-bold text-lg">Export Report</h4>
                <p class="text-sm opacity-80">Download statements</p>
            </div>
        </a>

        <button onclick="alert('Send Invoice feature coming soon!')" class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 text-white p-6 rounded-2xl shadow-xl hover:scale-105 transition-transform flex items-center gap-4">
            <i class="fas fa-paper-plane text-4xl"></i>
            <div class="text-left">
                <h4 class="font-bold text-lg">Send Invoice</h4>
                <p class="text-sm opacity-80">Email to customer</p>
            </div>
        </button>

        <button onclick="alert('Settings feature coming soon!')" class="bg-gradient-to-br from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-700 text-white p-6 rounded-2xl shadow-xl hover:scale-105 transition-transform flex items-center gap-4">
            <i class="fas fa-cog text-4xl"></i>
            <div class="text-left">
                <h4 class="font-bold text-lg">Settings</h4>
                <p class="text-sm opacity-80">Payment config</p>
            </div>
        </button>
    </div>
</div>

<script>
    // Auto-hide success/error messages after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.animate-pulse, .bg-red-100, .bg-green-100');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);

    // Stats cards animation on page load
    document.addEventListener('DOMContentLoaded', () => {
        const statsCards = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4 > div');
        statsCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Animate progress bars
        const progressBars = document.querySelectorAll('.bg-blue-500.h-2\\.5, .bg-green-500.h-2\\.5, .bg-yellow-500.h-2\\.5, .bg-purple-500.h-2\\.5');
        progressBars.forEach((bar, index) => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 500 + (index * 100));
        });
    });
</script>
@endsection
