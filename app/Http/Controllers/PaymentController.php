<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PaymentController extends Controller
{
    /**
     * Display payment management page with all payments
     */
    public function index(Request $request)
    {
        try {
            // Build query with filters
            $query = Booking::with(['user', 'facility']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('booking_reference', 'like', "%{$search}%")
                      ->orWhere('guest_name', 'like', "%{$search}%")
                      ->orWhere('guest_email', 'like', "%{$search}%")
                      ->orWhere('guest_phone', 'like', "%{$search}%")
                      ->orWhere('transaction_id', 'like', "%{$search}%");
                });
            }

            // Filter by payment status
            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('payment_status', $request->status);
            }

            // Filter by booking type
            if ($request->filled('booking_type') && $request->booking_type !== 'all') {
                $query->where('booking_type', $request->booking_type);
            }

            // Filter by payment method
            if ($request->filled('payment_method') && $request->payment_method !== 'all') {
                $query->where('payment_method', $request->payment_method);
            }

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Get paginated payments
            $payments = $query->latest()->paginate(20)->withQueryString();

            // Calculate comprehensive statistics
            $stats = $this->calculateStats($request);

            // Calculate payment methods distribution
            $paymentMethods = $this->getPaymentMethodsDistribution();

            // Get recent activity (last 10 transactions)
            $recentActivity = Booking::with(['user', 'facility'])
                ->whereNotNull('payment_status')
                ->latest()
                ->take(10)
                ->get();

            // Revenue by booking type
            $revenueByType = $this->getRevenueByType();

            return view('dashboard.payment', compact(
                'payments',
                'stats',
                'paymentMethods',
                'recentActivity',
                'revenueByType'
            ));

        } catch (\Exception $e) {
            Log::error('Payment Index Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load payments: ' . $e->getMessage());
        }
    }

    /**
     * Calculate comprehensive payment statistics
     */
    private function calculateStats($request = null)
    {
        // Base query for stats
        $baseQuery = Booking::query();

        // Apply same filters as main query
        if ($request && $request->filled('date_from')) {
            $baseQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request && $request->filled('date_to')) {
            $baseQuery->whereDate('created_at', '<=', $request->date_to);
        }

        // Total revenue (paid only)
        $totalRevenue = (clone $baseQuery)->where('payment_status', 'paid')->sum('total_price_rwf');

        // Pending payments
        $pendingAmount = (clone $baseQuery)->where('payment_status', 'pending')->sum('total_price_rwf');
        $pendingCount = (clone $baseQuery)->where('payment_status', 'pending')->count();

        // Completed payments
        $completedAmount = $totalRevenue;
        $completedCount = (clone $baseQuery)->where('payment_status', 'paid')->count();

        // Failed payments
        $failedAmount = (clone $baseQuery)->where('payment_status', 'failed')->sum('total_price_rwf');
        $failedCount = (clone $baseQuery)->where('payment_status', 'failed')->count();

        // Refunded payments
        $refundedAmount = (clone $baseQuery)->where('payment_status', 'refunded')->sum('total_price_rwf');
        $refundedCount = (clone $baseQuery)->where('payment_status', 'refunded')->count();

        // Processing payments
        $processingAmount = (clone $baseQuery)->where('payment_status', 'processing')->sum('total_price_rwf');
        $processingCount = (clone $baseQuery)->where('payment_status', 'processing')->count();

        // Calculate growth percentage (compared to last month)
        $currentMonthRevenue = Booking::where('payment_status', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price_rwf');

        $lastMonthRevenue = Booking::where('payment_status', 'paid')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_price_rwf');

        if ($lastMonthRevenue > 0) {
            $growthPercentage = round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1);
        } else {
            $growthPercentage = $currentMonthRevenue > 0 ? 100 : 0;
        }

        // Today's revenue
        $todayRevenue = Booking::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total_price_rwf');

        // This week's revenue
        $weekRevenue = Booking::where('payment_status', 'paid')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total_price_rwf');

        // Average transaction value
        $avgTransactionValue = $completedCount > 0
            ? round($totalRevenue / $completedCount, 2)
            : 0;

        return [
            'total_revenue' => $totalRevenue,
            'total_revenue_formatted' => number_format($totalRevenue, 0) . ' RWF',
            'total_revenue_usd' => round($totalRevenue / 1000, 2),

            'pending_amount' => $pendingAmount,
            'pending_count' => $pendingCount,
            'pending_formatted' => number_format($pendingAmount, 0) . ' RWF',

            'completed_amount' => $completedAmount,
            'completed_count' => $completedCount,
            'completed_formatted' => number_format($completedAmount, 0) . ' RWF',

            'failed_amount' => $failedAmount,
            'failed_count' => $failedCount,
            'failed_formatted' => number_format($failedAmount, 0) . ' RWF',

            'refunded_amount' => $refundedAmount,
            'refunded_count' => $refundedCount,
            'refunded_formatted' => number_format($refundedAmount, 0) . ' RWF',

            'processing_amount' => $processingAmount,
            'processing_count' => $processingCount,
            'processing_formatted' => number_format($processingAmount, 0) . ' RWF',

            'growth_percentage' => $growthPercentage,
            'today_revenue' => $todayRevenue,
            'today_revenue_formatted' => number_format($todayRevenue, 0) . ' RWF',
            'week_revenue' => $weekRevenue,
            'week_revenue_formatted' => number_format($weekRevenue, 0) . ' RWF',
            'avg_transaction' => $avgTransactionValue,
            'avg_transaction_formatted' => number_format($avgTransactionValue, 0) . ' RWF',
        ];
    }

    /**
     * Get payment methods distribution
     */
    private function getPaymentMethodsDistribution()
    {
        $totalPayments = Booking::where('payment_status', 'paid')->count();
        $totalPayments = $totalPayments > 0 ? $totalPayments : 1; // Prevent division by zero

        $methods = [
            'cash' => ['name' => 'Cash', 'color' => 'green', 'icon' => '💵'],
            'momo' => ['name' => 'MTN Mobile Money', 'color' => 'yellow', 'icon' => '📱'],
            'card' => ['name' => 'Card Payment', 'color' => 'blue', 'icon' => '💳'],
            'bank' => ['name' => 'Bank Transfer', 'color' => 'purple', 'icon' => '🏦'],
        ];

        $distribution = [];

        foreach ($methods as $key => $method) {
            $count = Booking::where('payment_status', 'paid')
                ->where('payment_method', $key)
                ->count();

            $amount = Booking::where('payment_status', 'paid')
                ->where('payment_method', $key)
                ->sum('total_price_rwf');

            $distribution[] = [
                'name' => $method['name'],
                'icon' => $method['icon'],
                'color' => $method['color'],
                'count' => $count,
                'percentage' => round(($count / $totalPayments) * 100, 1),
                'amount' => $amount,
                'amount_formatted' => number_format($amount, 0) . ' RWF',
            ];
        }

        // Sort by percentage descending
        usort($distribution, function($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });

        return $distribution;
    }

    /**
     * Get revenue breakdown by booking type
     */
    private function getRevenueByType()
    {
        $types = [
            'restaurant' => ['name' => 'Restaurants', 'icon' => '🍽️', 'color' => 'orange'],
            'coffee' => ['name' => 'Coffee Shops', 'icon' => '☕', 'color' => 'amber'],
            'hotel' => ['name' => 'Hotels', 'icon' => '🏨', 'color' => 'blue'],
            'transport' => ['name' => 'Transport', 'icon' => '🚗', 'color' => 'green'],
        ];

        $revenue = [];

        foreach ($types as $key => $type) {
            $amount = Booking::where('payment_status', 'paid')
                ->where('booking_type', $key)
                ->sum('total_price_rwf');

            $count = Booking::where('payment_status', 'paid')
                ->where('booking_type', $key)
                ->count();

            $revenue[] = [
                'type' => $key,
                'name' => $type['name'],
                'icon' => $type['icon'],
                'color' => $type['color'],
                'amount' => $amount,
                'amount_formatted' => number_format($amount, 0) . ' RWF',
                'count' => $count,
            ];
        }

        // Sort by amount descending
        usort($revenue, function($a, $b) {
            return $b['amount'] <=> $a['amount'];
        });

        return $revenue;
    }

    /**
     * Show payment details
     */
    public function show($id)
    {
        try {
            $payment = Booking::with(['user', 'facility', 'room'])
                ->findOrFail($id);

            return view('dashboard.payment-show', compact('payment'));

        } catch (\Exception $e) {
            Log::error('Payment Show Error: ' . $e->getMessage());
            return back()->with('error', 'Payment not found.');
        }
    }

    /**
     * Confirm pending payment - THIS WAS THE MISSING METHOD
     */
    public function confirmPayment($id)
    {
        try {
            DB::beginTransaction();

            $payment = Booking::findOrFail($id);

            if (!in_array($payment->payment_status, ['pending', 'processing'])) {
                return back()->with('error', 'Only pending or processing payments can be confirmed.');
            }

            $payment->update([
                'payment_status' => 'paid',
                'status' => $payment->status === 'pending' ? 'confirmed' : $payment->status,
                'transaction_id' => $payment->transaction_id ?? 'MANUAL-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'paid_at' => now(),
            ]);

            DB::commit();

            Log::info('Payment confirmed manually', [
                'payment_id' => $payment->id,
                'reference' => $payment->booking_reference,
                'amount' => $payment->total_price_rwf,
            ]);

            return back()->with('success', 'Payment confirmed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Confirm Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to confirm payment: ' . $e->getMessage());
        }
    }

    /**
     * Confirm pending payment (alternative method name)
     */
    public function confirm($id)
    {
        return $this->confirmPayment($id);
    }

    /**
     * Retry failed payment
     */
    public function retry($id)
    {
        try {
            $payment = Booking::findOrFail($id);

            if ($payment->payment_status !== 'failed') {
                return back()->with('error', 'Only failed payments can be retried.');
            }

            $payment->update([
                'payment_status' => 'pending',
            ]);

            Log::info('Payment retry initiated', [
                'payment_id' => $payment->id,
                'reference' => $payment->booking_reference,
            ]);

            return back()->with('success', 'Payment retry initiated. Please complete the payment process.');

        } catch (\Exception $e) {
            Log::error('Payment Retry Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to retry payment: ' . $e->getMessage());
        }
    }

    /**
     * Download invoice for paid payment
     */
    public function invoice($id)
    {
        try {
            $payment = Booking::with(['user', 'facility', 'room'])
                ->findOrFail($id);

            if ($payment->payment_status !== 'paid') {
                return back()->with('error', 'Invoice can only be generated for paid payments.');
            }

            // Generate PDF invoice
            $pdf = PDF::loadView('dashboard.payment-invoice', compact('payment'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'sans-serif'
                ]);

            $filename = 'invoice-' . $payment->booking_reference . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Invoice Generation Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Failed to generate invoice: ' . $e->getMessage());
        }
    }

    /**
     * Export payments to CSV
     */
    public function export(Request $request)
    {
        try {
            // Build query with same filters as index
            $query = Booking::with(['user', 'facility']);

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('payment_status', $request->status);
            }

            if ($request->filled('booking_type') && $request->booking_type !== 'all') {
                $query->where('booking_type', $request->booking_type);
            }

            if ($request->filled('payment_method') && $request->payment_method !== 'all') {
                $query->where('payment_method', $request->payment_method);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $payments = $query->latest()->get();

            // Create CSV content
            $filename = 'payments_export_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($payments) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                // CSV Headers
                fputcsv($file, [
                    'Reference',
                    'Customer Name',
                    'Email',
                    'Phone',
                    'Booking Type',
                    'Facility',
                    'Amount (RWF)',
                    'Amount (USD)',
                    'Payment Method',
                    'Payment Status',
                    'Booking Status',
                    'Transaction ID',
                    'Date',
                    'Time'
                ]);

                // CSV Data
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->booking_reference ?? 'N/A',
                        $payment->guest_name ?? $payment->user->name ?? 'N/A',
                        $payment->guest_email ?? $payment->user->email ?? 'N/A',
                        $payment->guest_phone ?? 'N/A',
                        ucfirst($payment->booking_type),
                        $payment->facility->name ?? 'N/A',
                        number_format($payment->total_price_rwf ?? 0, 2),
                        number_format($payment->total_price_usd ?? 0, 2),
                        ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')),
                        ucfirst($payment->payment_status),
                        ucfirst($payment->status),
                        $payment->transaction_id ?? 'N/A',
                        $payment->created_at->format('Y-m-d'),
                        $payment->created_at->format('H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Payment Export Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export payments: ' . $e->getMessage());
        }
    }

    /**
     * Mark payment as refunded
     */
    public function refund(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $payment = Booking::findOrFail($id);

            if ($payment->payment_status !== 'paid') {
                return back()->with('error', 'Only paid payments can be refunded.');
            }

            // Validate refund amount
            $refundAmount = $request->input('refund_amount', $payment->total_price_rwf);

            if ($refundAmount > $payment->total_price_rwf) {
                return back()->with('error', 'Refund amount cannot exceed payment amount.');
            }

            $payment->update([
                'payment_status' => 'refunded',
                'status' => 'cancelled',
                'refund_amount' => $refundAmount,
                'refund_status' => 'completed',
                'cancelled_at' => now(),
                'cancelled_reason' => $request->input('refund_reason', 'Payment refunded by admin'),
            ]);

            DB::commit();

            Log::info('Payment refunded', [
                'payment_id' => $payment->id,
                'reference' => $payment->booking_reference,
                'refund_amount' => $refundAmount,
            ]);

            return back()->with('success', 'Payment refunded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Refund Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to refund payment: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update payment status
     */
    public function bulkUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_ids' => 'required|array',
                'payment_ids.*' => 'exists:bookings,id',
                'action' => 'required|in:confirm,retry,refund',
            ]);

            DB::beginTransaction();

            $count = 0;
            foreach ($validated['payment_ids'] as $paymentId) {
                $payment = Booking::find($paymentId);

                if ($validated['action'] === 'confirm' && in_array($payment->payment_status, ['pending', 'processing'])) {
                    $payment->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                        'paid_at' => now(),
                    ]);
                    $count++;
                } elseif ($validated['action'] === 'retry' && $payment->payment_status === 'failed') {
                    $payment->update(['payment_status' => 'pending']);
                    $count++;
                } elseif ($validated['action'] === 'refund' && $payment->payment_status === 'paid') {
                    $payment->update([
                        'payment_status' => 'refunded',
                        'status' => 'cancelled',
                        'refund_status' => 'completed',
                        'cancelled_at' => now(),
                    ]);
                    $count++;
                }
            }

            DB::commit();

            return back()->with('success', "Successfully processed {$count} payment(s).");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk Update Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to process bulk update: ' . $e->getMessage());
        }
    }
}
