<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // إحصائيات عامة
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),

            // إيرادات اليوم
            'today_revenue' => Booking::whereDate('created_at', Carbon::today())
                ->whereNotIn('status', ['cancelled'])
                ->sum('total_price_rwf'),

            // إيرادات هذا الشهر
            'month_revenue' => Booking::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->whereNotIn('status', ['cancelled'])
                ->sum('total_price_rwf'),

            // إيرادات السنة
            'year_revenue' => Booking::whereYear('created_at', Carbon::now()->year)
                ->whereNotIn('status', ['cancelled'])
                ->sum('total_price_rwf'),

            // حسب النوع
            'hotel_bookings' => Booking::where('booking_type', 'hotel')->count(),
            'restaurant_orders' => Booking::where('booking_type', 'restaurant')->count(),
            'coffee_orders' => Booking::where('booking_type', 'coffee')->count(),
            'transport_bookings' => Booking::where('booking_type', 'transport')->count(),
        ];

        // أحدث 10 حجوزات
        $recent_bookings = Booking::with(['facility', 'room', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // إحصائيات إضافية للـ Admin
        if ($user && $user->role === 'admin') {
            $stats['total_users'] = User::count();
            $stats['total_facilities'] = Facility::count();
            $stats['total_rooms'] = Room::count();

            // أفضل المرافق
            $stats['top_facilities'] = Booking::select('facility_id', DB::raw('COUNT(*) as booking_count'))
                ->whereNotNull('facility_id')
                ->groupBy('facility_id')
                ->with('facility')
                ->orderBy('booking_count', 'desc')
                ->limit(5)
                ->get();
        }

        // إحصائيات المستخدم العادي
        if ($user && $user->role !== 'admin') {
            $stats['my_bookings'] = Booking::where('user_id', $user->id)->count();
            $stats['my_pending'] = Booking::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count();
            $stats['my_confirmed'] = Booking::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->count();
        }

        // بيانات الرسم البياني - آخر 7 أيام
        $chart_data = $this->getChartData();

        return view('dashboard', compact('stats', 'recent_bookings', 'chart_data'));
    }

    /**
     * بيانات الرسم البياني للـ 7 أيام الماضية
     */
    private function getChartData()
    {
        $days = [];
        $bookings = [];
        $revenue = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $days[] = $date->format('M d');

            $dayBookings = Booking::whereDate('created_at', $date)->count();
            $bookings[] = $dayBookings;

            $dayRevenue = Booking::whereDate('created_at', $date)
                ->whereNotIn('status', ['cancelled'])
                ->sum('total_price_rwf');
            $revenue[] = round($dayRevenue, 2);
        }

        return [
            'days' => $days,
            'bookings' => $bookings,
            'revenue' => $revenue,
        ];
    }

    /**
     * Dashboard للـ Hotel
     */
    public function hotel()
    {
        $stats = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('availability', 'available')->count(),
            'occupied_rooms' => Room::where('availability', 'occupied')->count(),
            'hotel_bookings' => Booking::where('booking_type', 'hotel')->count(),
            'pending_checkins' => Booking::where('booking_type', 'hotel')
                ->where('status', 'confirmed')
                ->whereDate('checkin_date', '>=', Carbon::today())
                ->count(),
            'today_revenue' => Booking::where('booking_type', 'hotel')
                ->whereDate('created_at', Carbon::today())
                ->sum('total_price_rwf'),
        ];

        $recent_hotel_bookings = Booking::where('booking_type', 'hotel')
            ->with(['facility', 'room', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.hotel', compact('stats', 'recent_hotel_bookings'));
    }

    /**
     * Dashboard للـ Restaurant
     */
    public function restaurant()
    {
        $stats = [
            'total_orders' => Booking::where('booking_type', 'restaurant')->count(),
            'pending_orders' => Booking::where('booking_type', 'restaurant')
                ->where('status', 'pending')
                ->count(),
            'today_orders' => Booking::where('booking_type', 'restaurant')
                ->whereDate('created_at', Carbon::today())
                ->count(),
            'today_revenue' => Booking::where('booking_type', 'restaurant')
                ->whereDate('created_at', Carbon::today())
                ->sum('total_price_rwf'),
            'month_revenue' => Booking::where('booking_type', 'restaurant')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_price_rwf'),
        ];

        $recent_orders = Booking::where('booking_type', 'restaurant')
            ->with(['facility', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.restaurant', compact('stats', 'recent_orders'));
    }

    /**
     * Dashboard للـ Coffee Shop
     */
    public function coffee()
    {
        $stats = [
            'total_orders' => Booking::where('booking_type', 'coffee')->count(),
            'pending_orders' => Booking::where('booking_type', 'coffee')
                ->where('status', 'pending')
                ->count(),
            'today_orders' => Booking::where('booking_type', 'coffee')
                ->whereDate('created_at', Carbon::today())
                ->count(),
            'today_revenue' => Booking::where('booking_type', 'coffee')
                ->whereDate('created_at', Carbon::today())
                ->sum('total_price_rwf'),
            'month_revenue' => Booking::where('booking_type', 'coffee')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_price_rwf'),
        ];

        $recent_orders = Booking::where('booking_type', 'coffee')
            ->with(['facility', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.coffee', compact('stats', 'recent_orders'));
    }

    /**
     * Dashboard للـ Transport
     */
    public function transport()
    {
        $stats = [
            'total_bookings' => Booking::where('booking_type', 'transport')->count(),
            'pending_bookings' => Booking::where('booking_type', 'transport')
                ->where('status', 'pending')
                ->count(),
            'today_bookings' => Booking::where('booking_type', 'transport')
                ->whereDate('created_at', Carbon::today())
                ->count(),
            'today_revenue' => Booking::where('booking_type', 'transport')
                ->whereDate('created_at', Carbon::today())
                ->sum('total_price_rwf'),
            'month_revenue' => Booking::where('booking_type', 'transport')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_price_rwf'),
        ];

        $recent_bookings = Booking::where('booking_type', 'transport')
            ->with(['facility', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.transport', compact('stats', 'recent_bookings'));
    }

    /**
     * Reports Page
     */
    public function reports(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $reports = [
            'total_revenue' => Booking::whereBetween('created_at', [$startDate, $endDate])
                ->whereNotIn('status', ['cancelled'])
                ->sum('total_price_rwf'),

            'total_bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),

            'by_type' => Booking::select('booking_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_price_rwf) as revenue'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('booking_type')
                ->get(),

            'by_status' => Booking::select('status', DB::raw('COUNT(*) as count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('status')
                ->get(),

            'by_facility' => Booking::select('facility_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_price_rwf) as revenue'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('facility_id')
                ->with('facility')
                ->groupBy('facility_id')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];

        return view('dashboard.reports', compact('reports', 'startDate', 'endDate'));
    }
}
