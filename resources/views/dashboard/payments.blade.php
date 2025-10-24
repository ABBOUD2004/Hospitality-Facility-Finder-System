@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">
    <!-- Navigation Tabs -->
    <nav class="flex flex-wrap gap-2 bg-white p-4 rounded-lg shadow">
        <a href="{{ route('dashboard.hotel') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F37021] rounded-lg transition font-semibold text-[#F37021] hover:bg-[#F37021]/10">
            <i data-feather="home" class="w-5 h-5"></i> Hotels
        </a>

        <a href="{{ route('dashboard.restaurant') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F37021] rounded-lg transition font-semibold text-[#F37021] hover:bg-[#F37021]/10">
            <i data-feather="utensils" class="w-5 h-5"></i> Restaurants
        </a>

        <a href="{{ route('dashboard.coffee') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F37021] rounded-lg transition font-semibold text-[#F37021] hover:bg-[#F37021]/10">
            <i data-feather="coffee" class="w-5 h-5"></i> Coffee Shops
        </a>

        <a href="{{ route('dashboard.bookings.index') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F37021] rounded-lg transition font-semibold text-[#F37021] hover:bg-[#F37021]/10">
            <i data-feather="calendar" class="w-5 h-5"></i> Bookings
        </a>

        <a href="{{ route('dashboard.payment') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F37021] rounded-lg transition font-semibold bg-[#F37021] text-white">
            <i data-feather="credit-card" class="w-5 h-5"></i> Payments
        </a>

        <a href="{{ route('dashboard.transport') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F37021] rounded-lg transition font-semibold text-[#F37021] hover:bg-[#F37021]/10">
            <i data-feather="truck" class="w-5 h-5"></i> Transportation
        </a>
    </nav>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#F37021] to-orange-600 rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-4xl font-bold mb-2 flex items-center gap-3">
            <i data-feather="dollar-sign" class="w-10 h-10"></i>
            Payment Management
        </h1>
        <p class="text-orange-100 text-lg">Monitor and manage all payments and transactions</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <p class="text-green-700 font-semibold">✓ {{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <p class="text-red-700 font-semibold">✗ {{ session('error') }}</p>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Total Revenue</h3>
                <i data-feather="trending-up" class="w-8 h-8 opacity-50"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($stats['total_revenue'], 2) }}</p>
            <p class="text-sm opacity-75 mt-1">
                {{ $stats['growth_percentage'] > 0 ? '+' : '' }}{{ $stats['growth_percentage'] }}% from last month
            </p>
        </div>

        <!-- Pending Payments -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Pending</h3>
                <i data-feather="clock" class="w-8 h-8 opacity-50"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($stats['pending_amount'], 2) }}</p>
            <p class="text-sm opacity-75 mt-1">{{ $stats['pending_count'] }} transactions</p>
        </div>

        <!-- Completed Payments -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Completed</h3>
                <i data-feather="check-circle" class="w-8 h-8 opacity-50"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($stats['completed_amount'], 2) }}</p>
            <p class="text-sm opacity-75 mt-1">{{ $stats['completed_count'] }} transactions</p>
        </div>

        <!-- Failed Payments -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Failed</h3>
                <i data-feather="x-circle" class="w-8 h-8 opacity-50"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($stats['failed_amount'], 2) }}</p>
            <p class="text-sm opacity-75 mt-1">{{ $stats['failed_count'] }} transactions</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i data-feather="filter" class="w-5 h-5"></i>
            Filter Payments
        </h2>

        <form method="GET" action="{{ route('dashboard.payment') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Status</label>
                    <select name="status" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#F37021]">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Booking Type</label>
                    <select name="booking_type" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#F37021]">
                        <option value="">All Types</option>
                        <option value="hotel" {{ request('booking_type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                        <option value="restaurant" {{ request('booking_type') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                        <option value="coffee" {{ request('booking_type') == 'coffee' ? 'selected' : '' }}>Coffee Shop</option>
                        <option value="transport" {{ request('booking_type') == 'transport' ? 'selected' : '' }}>Transport</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#F37021]">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#F37021]">
                </div>
            </div>

            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-[#F37021] text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition font-semibold flex items-center gap-2">
                    <i data-feather="search" class="w-4 h-4"></i> Apply Filters
                </button>
                <a href="{{ route('dashboard.payment') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition font-semibold flex items-center gap-2">
                    <i data-feather="x" class="w-4 h-4"></i> Clear
                </a>
                <a href="{{ route('dashboard.payments.export', request()->all()) }}" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition font-semibold flex items-center gap-2 ml-auto">
                    <i data-feather="download" class="w-4 h-4"></i> Export CSV
                </a>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-[#F37021] text-white p-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i data-feather="list" class="w-6 h-6"></i>
                Payment Transactions
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b-2 border-gray-300">
                        <th class="px-6 py-4 text-left font-bold text-gray-700">ID</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700">Customer</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700">Contact</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700">Booking Type</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700">Amount</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700">Payment Method</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700">Date</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800">#PAY-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $payment->guest_name ?? $payment->user->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->guest_email ?? $payment->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $payment->guest_phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $typeColors = [
                                        'hotel' => 'bg-blue-100 text-blue-800',
                                        'restaurant' => 'bg-purple-100 text-purple-800',
                                        'coffee' => 'bg-orange-100 text-orange-800',
                                        'transport' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $typeIcons = [
                                        'hotel' => 'home',
                                        'restaurant' => 'utensils',
                                        'coffee' => 'coffee',
                                        'transport' => 'truck',
                                    ];
                                @endphp
                                <span class="{{ $typeColors[$payment->booking_type] ?? 'bg-gray-100 text-gray-800' }} px-3 py-1 rounded-full text-sm font-semibold">
                                    <i data-feather="{{ $typeIcons[$payment->booking_type] ?? 'circle' }}" class="w-3 h-3 inline"></i>
                                    {{ ucfirst($payment->booking_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold
                                {{ $payment->payment_status == 'paid' ? 'text-green-600' : '' }}
                                {{ $payment->payment_status == 'pending' ? 'text-yellow-600' : '' }}
                                {{ $payment->payment_status == 'failed' ? 'text-red-600' : '' }}">
                                ${{ number_format($payment->total_price, 2) }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                <i data-feather="credit-card" class="w-4 h-4 inline"></i>
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                {{ $payment->created_at->format('M d, Y') }}<br>
                                <span class="text-xs text-gray-400">{{ $payment->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'paid' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'refunded' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $statusIcons = [
                                        'paid' => 'check',
                                        'pending' => 'clock',
                                        'failed' => 'x',
                                        'refunded' => 'rotate-ccw',
                                    ];
                                @endphp
                                <span class="{{ $statusColors[$payment->payment_status] ?? 'bg-gray-100 text-gray-800' }} px-4 py-2 rounded-lg font-bold inline-flex items-center gap-1">
                                    <i data-feather="{{ $statusIcons[$payment->payment_status] ?? 'circle' }}" class="w-4 h-4"></i>
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('dashboard.payments.show', $payment->id) }}"
                                       class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition" title="View Details">
                                        <i data-feather="eye" class="w-4 h-4"></i>
                                    </a>

                                    @if($payment->payment_status == 'pending')
                                        <form action="{{ route('dashboard.payments.confirm', $payment->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600 transition" title="Confirm Payment">
                                                <i data-feather="check-circle" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($payment->payment_status == 'failed')
                                        <form action="{{ route('dashboard.payments.retry', $payment->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-orange-500 text-white p-2 rounded-lg hover:bg-orange-600 transition" title="Retry Payment">
                                                <i data-feather="refresh-cw" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($payment->payment_status == 'paid')
                                        <a href="{{ route('dashboard.payments.invoice', $payment->id) }}"
                                           class="bg-purple-500 text-white p-2 rounded-lg hover:bg-purple-600 transition" title="Download Invoice">
                                            <i data-feather="download" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-gray-500 py-8 text-lg">
                                <i data-feather="info" class="w-8 h-8 inline mb-2"></i>
                                <p>No payment transactions found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t">
            <div class="text-gray-600">
                Showing <span class="font-semibold">{{ $payments->firstItem() ?? 0 }}</span> to
                <span class="font-semibold">{{ $payments->lastItem() ?? 0 }}</span> of
                <span class="font-semibold">{{ $payments->total() }}</span> transactions
            </div>
            <div>
                {{ $payments->links() }}
            </div>
        </div>
    </div>

    <!-- Payment Methods Chart & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payment Methods Distribution -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-feather="pie-chart" class="w-5 h-5"></i>
                Payment Methods
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-blue-500 rounded"></div>
                        <span class="text-gray-700">Credit Card</span>
                    </div>
                    <span class="font-bold text-gray-800">{{ $paymentMethods['credit_card'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $paymentMethods['credit_card'] }}%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                        <span class="text-gray-700">Mobile Wallet</span>
                    </div>
                    <span class="font-bold text-gray-800">{{ $paymentMethods['mobile_wallet'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $paymentMethods['mobile_wallet'] }}%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                        <span class="text-gray-700">Cash</span>
                    </div>
                    <span class="font-bold text-gray-800">{{ $paymentMethods['cash'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $paymentMethods['cash'] }}%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-purple-500 rounded"></div>
                        <span class="text-gray-700">Bank Transfer</span>
                    </div>
                    <span class="font-bold text-gray-800">{{ $paymentMethods['bank_transfer'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $paymentMethods['bank_transfer'] }}%"></div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-feather="activity" class="w-5 h-5"></i>
                Recent Activity
            </h3>
            <div class="space-y-4">
                @foreach($recentActivity as $activity)
                    @php
                        $activityColors = [
                            'paid' => 'bg-green-50',
                            'pending' => 'bg-yellow-50',
                            'failed' => 'bg-red-50',
                            'refunded' => 'bg-gray-50',
                        ];
                        $activityIconColors = [
                            'paid' => 'bg-green-500',
                            'pending' => 'bg-yellow-500',
                            'failed' => 'bg-red-500',
                            'refunded' => 'bg-gray-500',
                        ];
                        $activityIcons = [
                            'paid' => 'check',
                            'pending' => 'clock',
                            'failed' => 'x',
                            'refunded' => 'rotate-ccw',
                        ];
                        $activityTexts = [
                            'paid' => 'Payment Received',
                            'pending' => 'Pending Payment',
                            'failed' => 'Payment Failed',
                            'refunded' => 'Refund Processed',
                        ];
                    @endphp
                    <div class="flex items-start gap-3 p-3 {{ $activityColors[$activity['status']] ?? 'bg-gray-50' }} rounded-lg">
                        <div class="{{ $activityIconColors[$activity['status']] ?? 'bg-gray-500' }} text-white p-2 rounded-full">
                            <i data-feather="{{ $activityIcons[$activity['status']] ?? 'circle' }}" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $activityTexts[$activity['status']] ?? 'Payment Activity' }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $activity['customer_name'] }} - ${{ number_format($activity['amount'], 2) }} for {{ ucfirst($activity['type']) }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i data-feather="zap" class="w-5 h-5"></i>
            Quick Actions
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <button onclick="alert('Manual payment form coming soon!')"
                    class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg hover:shadow-lg transition flex items-center gap-3">
                <i data-feather="plus-circle" class="w-6 h-6"></i>
                <span class="font-semibold">Manual Payment</span>
            </button>
            <a href="{{ route('dashboard.payments.export', request()->all()) }}"
               class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg hover:shadow-lg transition flex items-center gap-3">
                <i data-feather="download" class="w-6 h-6"></i>
                <span class="font-semibold">Export Report</span>
            </a>
            <button onclick="alert('Send invoice feature coming soon!')"
                    class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-lg hover:shadow-lg transition flex items-center gap-3">
                <i data-feather="send" class="w-6 h-6"></i>
                <span class="font-semibold">Send Invoice</span>
            </button>
            <button onclick="alert('Payment settings coming soon!')"
                    class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-lg hover:shadow-lg transition flex items-center gap-3">
                <i data-feather="settings" class="w-6 h-6"></i>
                <span class="font-semibold">Settings</span>
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace();

    // Confirmation dialogs
    document.querySelectorAll('form[action*="confirm"], form[action*="retry"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const action = this.action.includes('confirm') ? 'confirm' : 'retry';
            if (!confirm(`Are you sure you want to ${action} this payment?`)) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">
    <!-- Navigation Tabs -->
    <nav class="flex flex-wrap gap-2 bg-white p-4 rounded-lg shadow">
        <a href="{{ route('dashboard.hotel') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F37021] rounded-lg transition font-semibold
           {{ request()->routeIs('dashboard.hotel') ? 'bg-[#F37021] text-white' : 'text-[#F37021] hover:bg-[#F37021]/10' }}">
            <i data-feather="home" class="w-5 h-5"></i> Hotels
        </a>

        <a href="{{ route('dashboard.restaurant') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F37021] rounded-lg transition font-semibold
           {{ request()->routeIs('dashboard.restaurant') ? 'bg-[#F37021] text-white' : 'text-[#F37021] hover:bg-[#F37021]/10' }}">
            <i data-feather="utensils" class="w-5 h-5"></i> Restaurants
        </a>

        <a href="{{ route('dashboard.coffee') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F37021] rounded-lg transition font-semibold
           {{ request()->routeIs('dashboard.coffee') ? 'bg-[#F37021] text-white' : 'text-[#F37021] hover:bg-[#F37021]/10' }}">
            <i data-feather="coffee" class="w-5 h-5"></i> Coffee Shops
        </a>

        <a href="{{ route('dashboard.bookings.index') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F37021] rounded-lg transition font-semibold
           {{ request()->routeIs('dashboard.bookings.*') ? 'bg-[#F37021] text-white' : 'text-[#F37021] hover:bg-[#F37021]/10' }}">
            <i data-feather="calendar" class="w-5 h-5"></i> Bookings
        </a>

        <a href="{{ route('dashboard.payment') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F37021] rounded-lg transition font-semibold
           {{ request()->routeIs('dashboard.payment') ? 'bg-[#F37021] text-white' : 'text-[#F37021] hover:bg-[#F37021]/10' }}">
            <i data-feather="credit-card" class="w-5 h-5"></i> Payments
        </a>

        <a href="{{ route('dashboard.transport') }}"
           class="flex items-center gap-2 px-4 py-2 border-2 border-[#F37021] rounded-lg transition font-semibold
           {{ request()->routeIs('dashboard.transport') ? 'bg-[#F37021] text-white' : 'text-[#F37021] hover:bg-[#F37021]/10' }}">
            <i data-feather="truck" class="w-5 h-5"></i> Transportation
        </a>
    </nav>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#F37021] to-orange-600 rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-4xl font-bold mb-2 flex items-center gap-3">
            <i data-feather="dollar-sign" class="w-10 h-10"></i>
            Payment Management
        </h1>
        <p class="text-orange-100 text-lg">Monitor and manage all payments and transactions</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Total Revenue</h3>
                <i data-feather="trending-up" class="w-8 h-8 opacity-50"></i>
            </div>
            <p class="text-3xl font-bold">$45,280</p>
            <p class="text-sm opacity-75 mt-1">+12% from last month</p>
        </div>

        <!-- Pending Payments -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Pending</h3>
                <i data-feather="clock" class="w-8 h-8 opacity-50"></i>
            </div>
            <p class="text-3xl font-bold">$8,450</p>
            <p class="text-sm opacity-75 mt-1">15 transactions</p>
        </div>

        <!-- Completed Payments -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Completed</h3>
                <i data-feather="check-circle" class="w-8 h-8 opacity-50"></i>
            </div>
            <p class="text-3xl font-bold">$36,830</p>
            <p class="text-sm opacity-75 mt-1">142 transactions</p>
        </div>

        <!-- Failed Payments -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold opacity-90">Failed</h3>
                <i data-feather="x-circle" class="w-8 h-8 opacity-50"></i>
            </div>
            <p class="text-3xl font-bold">$2,150</p>
            <p class="text-sm opacity-75 mt-1">8 transactions</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i data-feather="filter" class="w-5 h-5"></i>
            Filter Payments
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Status</label>
                <select class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#F37021]">
                    <option value="">All Status</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Booking Type</label>
                <select class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#F37021]">
                    <option value="">All Types</option>
                    <option value="hotel">Hotel</option>
                    <option value="restaurant">Restaurant</option>
                    <option value="coffee">Coffee Shop</option>
                    <option value="transport">Transport</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Date From</label>
                <input type="date" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#F37021]">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Date To</label>
                <input type="date" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#F37021]">
            </div>
        </div>

        <div class="flex gap-3 mt-4">
            <button class="bg-[#F37021] text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition font-semibold flex items-center gap-2">
                <i data-feather="search" class="w-4 h-4"></i> Apply Filters
            </button>
            <button class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition font-semibold flex items-center gap-2">
                <i data-feather="x" class="w-4 h-4"></i> Clear
            </button>
            <button class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition font-semibold flex items-center gap-2 ml-auto">
                <i data-feather="download" class="w-4 h-4"></i> Export CSV
            </button>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-[#F37021] text-white p-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i data-feather="list" class="w-6 h-6"></i>
                Payment Transactions
            </h2>
            <div class="flex gap-2">
                <button class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">
                    <i data-feather="refresh-cw" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b-2 border-gray-300">
                        <th class="px-6 py-4 text-left font-bold text-gray-700">ID</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700">Customer</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700">Contact</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700">Booking Type</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700">Amount</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700">Payment Method</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700">Date</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample Row 1 - Paid -->
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-800">#PAY-001</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">Ahmed Ali</div>
                            <div class="text-sm text-gray-500">ahmed@example.com</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">+20 123 456 7890</td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                <i data-feather="home" class="w-3 h-3 inline"></i> Hotel
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-green-600">$2,500</td>
                        <td class="px-6 py-4 text-gray-700">
                            <i data-feather="credit-card" class="w-4 h-4 inline"></i> Credit Card
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            Jan 15, 2025<br>
                            <span class="text-xs text-gray-400">10:30 AM</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-bold inline-flex items-center gap-1">
                                <i data-feather="check" class="w-4 h-4"></i> Paid
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition" title="View Details">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600 transition" title="Download Invoice">
                                    <i data-feather="download" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Sample Row 2 - Pending -->
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-800">#PAY-002</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">Sara Mohamed</div>
                            <div class="text-sm text-gray-500">sara@example.com</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">+20 111 222 3333</td>
                        <td class="px-6 py-4">
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                                <i data-feather="utensils" class="w-3 h-3 inline"></i> Restaurant
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-yellow-600">$450</td>
                        <td class="px-6 py-4 text-gray-700">
                            <i data-feather="smartphone" class="w-4 h-4 inline"></i> Mobile Wallet
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            Jan 16, 2025<br>
                            <span class="text-xs text-gray-400">02:15 PM</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg font-bold inline-flex items-center gap-1">
                                <i data-feather="clock" class="w-4 h-4"></i> Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition" title="View Details">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600 transition" title="Confirm Payment">
                                    <i data-feather="check-circle" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Sample Row 3 - Failed -->
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-800">#PAY-003</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">Omar Hassan</div>
                            <div class="text-sm text-gray-500">omar@example.com</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">+20 100 200 3000</td>
                        <td class="px-6 py-4">
                            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold">
                                <i data-feather="coffee" class="w-3 h-3 inline"></i> Coffee Shop
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-red-600">$180</td>
                        <td class="px-6 py-4 text-gray-700">
                            <i data-feather="credit-card" class="w-4 h-4 inline"></i> Credit Card
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            Jan 16, 2025<br>
                            <span class="text-xs text-gray-400">04:45 PM</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-red-100 text-red-800 px-4 py-2 rounded-lg font-bold inline-flex items-center gap-1">
                                <i data-feather="x" class="w-4 h-4"></i> Failed
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition" title="View Details">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="bg-orange-500 text-white p-2 rounded-lg hover:bg-orange-600 transition" title="Retry Payment">
                                    <i data-feather="refresh-cw" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Sample Row 4 - Refunded -->
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-800">#PAY-004</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">Fatima Youssef</div>
                            <div class="text-sm text-gray-500">fatima@example.com</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">+20 155 666 7777</td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">
                                <i data-feather="truck" class="w-3 h-3 inline"></i> Transport
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-600">$320</td>
                        <td class="px-6 py-4 text-gray-700">
                            <i data-feather="dollar-sign" class="w-4 h-4 inline"></i> Cash
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            Jan 14, 2025<br>
                            <span class="text-xs text-gray-400">09:00 AM</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-800 px-4 py-2 rounded-lg font-bold inline-flex items-center gap-1">
                                <i data-feather="rotate-ccw" class="w-4 h-4"></i> Refunded
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition" title="View Details">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="bg-purple-500 text-white p-2 rounded-lg hover:bg-purple-600 transition" title="Refund Receipt">
                                    <i data-feather="file-text" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t">
            <div class="text-gray-600">
                Showing <span class="font-semibold">1-4</span> of <span class="font-semibold">165</span> transactions
            </div>
            <div class="flex gap-2">
                <button class="bg-white border-2 border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition font-semibold disabled:opacity-50" disabled>
                    Previous
                </button>
                <button class="bg-[#F37021] text-white px-4 py-2 rounded-lg font-semibold">1</button>
                <button class="bg-white border-2 border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition font-semibold">2</button>
                <button class="bg-white border-2 border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition font-semibold">3</button>
                <button class="bg-white border-2 border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition font-semibold">
                    Next
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Methods Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payment Methods Distribution -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-feather="pie-chart" class="w-5 h-5"></i>
                Payment Methods
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-blue-500 rounded"></div>
                        <span class="text-gray-700">Credit Card</span>
                    </div>
                    <span class="font-bold text-gray-800">45%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: 45%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                        <span class="text-gray-700">Mobile Wallet</span>
                    </div>
                    <span class="font-bold text-gray-800">30%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 30%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                        <span class="text-gray-700">Cash</span>
                    </div>
                    <span class="font-bold text-gray-800">15%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 15%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-purple-500 rounded"></div>
                        <span class="text-gray-700">Bank Transfer</span>
                    </div>
                    <span class="font-bold text-gray-800">10%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full" style="width: 10%"></div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-feather="activity" class="w-5 h-5"></i>
                Recent Activity
            </h3>
            <div class="space-y-4">
                <div class="flex items-start gap-3 p-3 bg-green-50 rounded-lg">
                    <div class="bg-green-500 text-white p-2 rounded-full">
                        <i data-feather="check" class="w-4 h-4"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">Payment Received</p>
                        <p class="text-sm text-gray-600">Ahmed Ali - $2,500 for Hotel Booking</p>
                        <p class="text-xs text-gray-400 mt-1">5 minutes ago</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-yellow-50 rounded-lg">
                    <div class="bg-yellow-500 text-white p-2 rounded-full">
                        <i data-feather="clock" class="w-4 h-4"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">Pending Payment</p>
                        <p class="text-sm text-gray-600">Sara Mohamed - $450 for Restaurant</p>
                        <p class="text-xs text-gray-400 mt-1">15 minutes ago</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-red-50 rounded-lg">
                    <div class="bg-red-500 text-white p-2 rounded-full">
                        <i data-feather="x" class="w-4 h-4"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">Payment Failed</p>
                        <p class="text-sm text-gray-600">Omar Hassan - $180 for Coffee Shop</p>
                        <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="bg-gray-500 text-white p-2 rounded-full">
                        <i data-feather="rotate-ccw" class="w-4 h-4"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">Refund Processed</p>
                        <p class="text-sm text-gray-600">Fatima Youssef - $320 for Transport</p>
                        <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i data-feather="zap" class="w-5 h-5"></i>
            Quick Actions
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <button class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg hover:shadow-lg transition flex items-center gap-3">
                <i data-feather="plus-circle" class="w-6 h-6"></i>
                <span class="font-semibold">Manual Payment</span>
            </button>
            <button class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg hover:shadow-lg transition flex items-center gap-3">
                <i data-feather="download" class="w-6 h-6"></i>
                <span class="font-semibold">Export Report</span>
            </button>
            <button class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-lg hover:shadow-lg transition flex items-center gap-3">
                <i data-feather="send" class="w-6 h-6"></i>
                <span class="font-semibold">Send Invoice</span>
            </button>
            <button class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-lg hover:shadow-lg transition flex items-center gap-3">
                <i data-feather="settings" class="w-6 h-6"></i>
                <span class="font-semibold">Settings</span>
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace();

    // Auto-refresh every 30 seconds
    setInterval(function() {
        // Add your refresh logic here
        console.log('Auto-refreshing payment data...');
    }, 30000);
</script>
@endsection
