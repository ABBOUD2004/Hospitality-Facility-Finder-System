<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * منع Double Submit
     */
    private function preventDoubleSubmit(Request $request): bool
    {
        $sessionKey = 'booking_submitted_' . session()->getId();

        if (session()->has($sessionKey) && session($sessionKey) + 5 > time()) {
            Log::warning('⚠️ Double submit prevented for session: ' . session()->getId());
            return true;
        }

        session([$sessionKey => time()]);
        return false;
    }

    /**
     * حذف session lock بعد نجاح العملية
     */
    private function clearSubmitLock(): void
    {
        session()->forget('booking_submitted_' . session()->getId());
    }

    /**
     * تحديد نوع Response حسب الطلب
     */
    private function isJsonRequest(Request $request): bool
    {
        return $request->ajax() || $request->wantsJson() || $request->expectsJson();
    }

    /**
     * إنشاء حجز/طلب جديد
     */
    public function store(Request $request)
    {
        Log::info('========== NEW ORDER/BOOKING REQUEST ==========');
        Log::info('Request Data:', $request->all());
        Log::info('Headers:', $request->headers->all());

        // التحقق من تسجيل الدخول (اختياري للمطاعم)
        $userId = Auth::id();
        Log::info('User ID: ' . ($userId ?? 'Guest'));

        // منع Double Submit
        if ($this->preventDoubleSubmit($request)) {
            return $this->respondDoubleSubmit($request);
        }

        try {
            DB::beginTransaction();

            // ✅ تحديد نوع الطلب وتوحيد coffee_shop إلى coffee
            $rawType = $request->input('booking_type', 'restaurant');
            $bookingType = $rawType === 'coffee_shop' ? 'coffee' : $rawType;

            Log::info('Raw Type: ' . $rawType);
            Log::info('Normalized Type: ' . $bookingType);

            // توجيه الطلب حسب النوع
            $booking = match($bookingType) {
                'restaurant' => $this->processRestaurantOrder($request, 'restaurant'),
                'coffee' => $this->processRestaurantOrder($request, 'coffee'),
                'hotel' => $this->processHotelBooking($request),
                'transport' => $this->processTransportBooking($request),
                default => throw new \Exception('نوع غير مدعوم: ' . $bookingType)
            };

            DB::commit();
            $this->clearSubmitLock();

            Log::info('✅ Order created successfully!');
            Log::info('Reference: ' . $booking->booking_reference);
            Log::info('Type: ' . $booking->booking_type);

            return $this->respondSuccess($request, $booking);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $this->clearSubmitLock();
            return $this->respondValidationError($request, $e);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->clearSubmitLock();
            return $this->respondGeneralError($request, $e);
        }
    }

    /**
     * ✅ معالجة طلب المطعم/الكوفي (مع تحديد النوع)
     */
 private function processRestaurantOrder(Request $request, string $type = 'restaurant'): Booking
{
    Log::info('📝 Processing Restaurant/Coffee Order - Type: ' . $type);
    Log::info('📥 Full Request:', $request->all());

    // ✅ ULTRA-FLEXIBLE VALIDATION - بيقبل أي حاجة
    $validated = $request->validate([
        'facility_id' => 'nullable|integer',
        'order_items' => 'nullable|string',
        'special_requests' => 'nullable|string',
        'total_price' => 'nullable|numeric',
        'total_price_rwf' => 'nullable|numeric',
        'number_of_guests' => 'nullable|integer',
    ]);

    Log::info('✅ Validation passed');

    $user = Auth::user();

    // ✅ Get facility_id
    $facilityId = $validated['facility_id']
        ?? \App\Models\Facility::where('type', $type)->first()->id
        ?? \App\Models\Facility::first()->id
        ?? 1;

    // ✅ Get values with fallbacks
    $orderItems = $validated['order_items'] ?? '[]';
    $totalPrice = $validated['total_price'] ?? $validated['total_price_rwf'] ?? 0;
    $totalPriceRwf = $validated['total_price_rwf'] ?? $validated['total_price'] ?? 0;
    $numGuests = $validated['number_of_guests'] ?? 1;
    $specialRequests = $validated['special_requests'] ?? null;

    // Generate order number
    $prefix = $type === 'coffee' ? 'CFE' : 'RST';
    $orderNumber = $prefix . '-' . date('Ymd') . '-' . strtoupper(Str::random(6));

    // Get user details
    $guestName = $user->name ?? 'Guest Customer';
    $nameParts = explode(' ', trim($guestName), 2);
    $firstname = $nameParts[0] ?? 'Guest';
    $lastname = $nameParts[1] ?? 'Customer';

    // ✅ Create booking
    $booking = Booking::create([
        'user_id' => $user->id ?? null,
        'facility_id' => $facilityId,
        'booking_type' => $type,
        'booking_reference' => $orderNumber,

        'guest_name' => $guestName,
        'guest_firstname' => $firstname,
        'guest_lastname' => $lastname,
        'guest_email' => $user->email ?? 'guest@example.com',
        'guest_phone' => $user->phone ?? 'N/A',

        'order_items' => $orderItems,
        'number_of_guests' => $numGuests,

        'reservation_date' => now(),
        'checkin_date' => now(),
        'checkout_date' => now(),
        'reservation_time' => now()->format('H:i'),
        'order_date' => now(),

        'total_price' => $totalPrice,
        'total_price_rwf' => $totalPriceRwf,
        'total_price_usd' => round($totalPriceRwf / 1300, 2),

        'delivery_address' => null,
        'payment_method' => 'cash',
        'payment_status' => 'pending',
        'special_requests' => $specialRequests,
        'status' => 'pending',
    ]);

    Log::info('✅ Order created successfully:', [
        'booking_id' => $booking->id,
        'reference' => $booking->booking_reference,
        'type' => $booking->booking_type,
        'facility_id' => $facilityId,
        'total' => $totalPrice,
    ]);

    return $booking;
}


    /**
     * معالجة حجز الفندق
     */
 private function processHotelBooking(Request $request): Booking
{
    Log::info('🏨 Processing Hotel Booking...');
    Log::info('Request Data:', $request->all());

    $validated = $request->validate([
        'facility_id' => 'required|exists:facilities,id',
        'room_id' => 'required|exists:rooms,id',

        'checkin_date' => 'required|date|after_or_equal:today',
        'checkout_date' => 'required|date|after:checkin_date',

        'adults' => 'required|integer|min:1|max:20',
        'children' => 'nullable|integer|min:0|max:10',
        'nights' => 'required|integer|min:1',

        'guest_firstname' => 'required|string|max:255',
        'guest_lastname' => 'required|string|max:255',
        'guest_email' => 'required|email|max:255',
        'guest_phone' => 'required|string|max:20',

        'total_price_rwf' => 'required|numeric|min:0',
        'payment_method' => 'required|string|max:50', // ✅ مرن

        'special_requests' => 'nullable|string|max:1000',
    ]);

    Log::info('✅ Validation passed');
    Log::info('Payment Method: ' . $validated['payment_method']); // ✅ Log

    $user = Auth::user();
    $checkIn = Carbon::parse($validated['checkin_date']);
    $checkOut = Carbon::parse($validated['checkout_date']);
    $nights = $validated['nights'];
    $room = Room::findOrFail($validated['room_id']);

    $bookingNumber = 'HTL-' . date('Ymd') . '-' . strtoupper(Str::random(6));

    $booking = Booking::create([
        'user_id' => $user->id,
        'facility_id' => $validated['facility_id'],
        'room_id' => $validated['room_id'],
        'booking_type' => 'hotel',
        'booking_reference' => $bookingNumber,

        'guest_name' => $validated['guest_firstname'] . ' ' . $validated['guest_lastname'],
        'guest_firstname' => $validated['guest_firstname'],
        'guest_lastname' => $validated['guest_lastname'],
        'guest_email' => $validated['guest_email'],
        'guest_phone' => $validated['guest_phone'],

        'checkin_date' => $checkIn,
        'checkout_date' => $checkOut,
        'nights' => $nights,
        'adults' => $validated['adults'],
        'children' => $validated['children'] ?? 0,
        'number_of_guests' => $validated['adults'] + ($validated['children'] ?? 0),

        'total_price_rwf' => $validated['total_price_rwf'],
        'total_price_usd' => round($validated['total_price_rwf'] / 1000, 2),
        'total_price' => $validated['total_price_rwf'],

        'payment_method' => $validated['payment_method'],
        'payment_status' => 'pending',
        'status' => 'pending',

        'special_requests' => $validated['special_requests'] ?? null,
    ]);

    Log::info('✅ Hotel booking created:', [
        'id' => $booking->id,
        'reference' => $booking->booking_reference,
        'room' => $room->name,
        'nights' => $nights,
        'total' => $booking->total_price_rwf,
    ]);

    return $booking;
}

    /**
     * معالجة حجز النقل
     */
   /**
 * ✅ معالجة حجز النقل + إنشاء سجل Transport تلقائياً
 */
private function processTransportBooking(Request $request): Booking
{
    Log::info('🚗 Processing Transport Booking...');
    Log::info('📥 Full Request:', $request->all());

    // ✅ FLEXIBLE VALIDATION
    $validated = $request->validate([
        // Trip Details - REQUIRED
        'pickup_location' => 'required|string|max:500',
        'destination' => 'required|string|max:500',
        'reservation_date' => 'required|date|after_or_equal:today',
        'reservation_time' => 'required',

        // Customer Info - REQUIRED
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'required|string|max:20',

        // Optional Fields
        'customer_email' => 'nullable|email|max:255',
        'number_of_guests' => 'nullable|integer|min:1|max:100',
        'vehicle_type' => 'nullable|in:car,van,truck,bus',
        'price' => 'nullable|numeric|min:0',
        'payment_method' => 'nullable|string|max:50',
        'notes' => 'nullable|string|max:2000',
        'distance' => 'nullable|string|max:100',
        'facility_id' => 'nullable|integer', // ✅ Changed: removed exists check
    ]);

    Log::info('✅ Validation passed:', $validated);

    $user = Auth::user();

    // ✅ Get values with fallbacks
    $customerName = $validated['customer_name'];
    $customerPhone = $validated['customer_phone'];
    $customerEmail = $validated['customer_email'] ?? ($user->email ?? null);
    $pickupLocation = $validated['pickup_location'];
    $destination = $validated['destination'];
    $reservationDate = $validated['reservation_date'];
    $reservationTime = $validated['reservation_time'];
    $numGuests = $validated['number_of_guests'] ?? 1;
    $vehicleType = $validated['vehicle_type'] ?? 'car';
    $price = $validated['price'] ?? 5000;
    $paymentMethod = $validated['payment_method'] ?? 'cash';
    $notes = $validated['notes'] ?? null;
    $distance = $validated['distance'] ?? null;

    // ✅ FIXED: Smart facility_id handling
    $facilityId = $validated['facility_id'] ?? null;

    // If facility_id is null or invalid, get first available facility
    if (!$facilityId || !\App\Models\Facility::where('id', $facilityId)->exists()) {
        $facilityId = \App\Models\Facility::first()->id ?? null;
        Log::warning('⚠️ Using fallback facility_id: ' . $facilityId);
    }

    // Split name
    $nameParts = explode(' ', trim($customerName), 2);
    $firstname = $nameParts[0];
    $lastname = $nameParts[1] ?? '';

    $bookingNumber = 'TRP-' . date('Ymd') . '-' . strtoupper(Str::random(6));

    // ✅ Create Booking
    $booking = Booking::create([
        'user_id' => $user->id ?? null,
        'facility_id' => $facilityId,
        'booking_type' => 'transport',
        'booking_reference' => $bookingNumber,

        // Guest Info
        'guest_name' => $customerName,
        'guest_firstname' => $firstname,
        'guest_lastname' => $lastname,
        'guest_email' => $customerEmail,
        'guest_phone' => $customerPhone,

        // Trip Details
        'pickup_location' => $pickupLocation,
        'destination' => $destination,
        'reservation_date' => Carbon::parse($reservationDate),
        'checkin_date' => Carbon::parse($reservationDate),
        'checkout_date' => Carbon::parse($reservationDate),
        'reservation_time' => $reservationTime,

        // Numbers
        'number_of_guests' => $numGuests,
        'adults' => $numGuests,
        'nights' => 1,

        // Pricing
        'total_price' => $price,
        'total_price_rwf' => $price,
        'total_price_usd' => round($price / 1300, 2),

        // Payment
        'payment_method' => $paymentMethod,
        'payment_status' => 'pending',
        'status' => 'pending',
    ]);

    Log::info('✅ Booking created:', [
        'id' => $booking->id,
        'reference' => $booking->booking_reference,
        'facility_id' => $facilityId,
    ]);

    // ✅ Create Transport record
    try {
        \App\Models\Transport::create([
            'user_id' => $user->id ?? null,
            'booking_id' => $booking->id,

            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'phone' => $customerPhone,

            'pickup_location' => $pickupLocation,
            'drop_location' => $destination,
            'pickup_date' => Carbon::parse($reservationDate),
            'pickup_time' => $reservationTime,

            'vehicle_type' => $vehicleType,
            'passengers' => $numGuests,
            'distance' => $distance,
            'price' => $price,
            'status' => 'pending',
            'notes' => $notes,
        ]);

        Log::info('✅ Transport record created');
    } catch (\Exception $e) {
        Log::error('❌ Transport creation failed: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
    }

    return $booking;
}

    /**
     * ✅ عرض جميع الحجوزات/الطلبات مع إحصائيات كاملة
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'facility', 'room'])
            ->orderBy('created_at', 'desc');

        // الفلترة
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('booking_type', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_phone', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        // ✅ إحصائيات شاملة
        $stats = [
            // إجمالي الحجوزات
            'total' => Booking::count(),

            // حسب الحالة
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'checked_in' => Booking::where('status', 'checked_in')->count(),

            // حسب النوع
            'hotel' => Booking::where('booking_type', 'hotel')->count(),
            'restaurant' => Booking::where('booking_type', 'restaurant')->count(),
            'coffee' => Booking::whereIn('booking_type', ['coffee', 'coffee_shop'])->count(),
            'transport' => Booking::where('booking_type', 'transport')->count(),

            // إيرادات اليوم (للحجوزات المؤكدة فقط)
            'revenue' => Booking::whereDate('created_at', today())
                ->whereIn('status', ['confirmed', 'checked_in', 'completed'])
                ->sum(DB::raw('COALESCE(total_price, total_price_rwf, 0)')),

            // إحصائيات إضافية مفيدة
            'today_bookings' => Booking::whereDate('created_at', today())->count(),
            'this_month' => Booking::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        Log::info('📊 Bookings Statistics:', $stats);

        return view('dashboard.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * ✅ قبول حجز النقل ونقله لإدارة النقل
     */
    public function acceptTransport(Booking $booking)
    {
        try {
            // التحقق من أنه حجز نقل
            if ($booking->booking_type !== 'transport') {
                return response()->json([
                    'success' => false,
                    'message' => 'هذا ليس حجز نقل'
                ], 400);
            }

            // التحقق من الحالة
            if ($booking->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'الحجز ليس في حالة انتظار'
                ], 400);
            }

            // تحديث الحالة إلى confirmed
            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            Log::info('✅ Transport booking accepted:', [
                'booking_id' => $booking->id,
                'reference' => $booking->booking_reference,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم قبول الحجز بنجاح',
                'booking' => $booking
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error accepting transport booking: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء قبول الحجز'
            ], 500);
        }
    }

    /**
     * ✅ عرض طلبات المطاعم والكوفي معاً
     */
    public function orders(Request $request)
    {
        $query = Booking::with(['user', 'facility'])
            ->whereIn('booking_type', ['restaurant', 'coffee'])
            ->orderBy('created_at', 'desc');

        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('booking_type', $request->type);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Booking::whereIn('booking_type', ['restaurant', 'coffee'])->count(),
            'restaurant' => Booking::where('booking_type', 'restaurant')->count(),
            'coffee' => Booking::where('booking_type', 'coffee')->count(),
            'pending' => Booking::whereIn('booking_type', ['restaurant', 'coffee'])
                ->where('status', 'pending')->count(),
            'completed' => Booking::whereIn('booking_type', ['restaurant', 'coffee'])
                ->where('status', 'completed')->count(),
            'revenue_today' => Booking::whereIn('booking_type', ['restaurant', 'coffee'])
                ->whereDate('created_at', today())
                ->whereIn('status', ['confirmed', 'completed'])
                ->sum(DB::raw('COALESCE(total_price, total_price_rwf, 0)')),
        ];

        return view('dashboard.orders', compact('orders', 'stats'));
    }

    /**
     * عرض حجوزات المستخدم
     */
    public function myBookings(Request $request)
    {
        $bookings = Booking::with(['facility', 'room'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.my-bookings', compact('bookings'));
    }

    /**
     * عرض تفاصيل الحجز/الطلب
     */
    public function show($id)
    {
        $booking = Booking::with(['user', 'facility', 'room'])->findOrFail($id);

        // التحقق من الصلاحيات
        $isAdmin = Auth::user()->role === 'admin'; // أو is_admin أو حسب الـ structure عندك
        if (!$isAdmin && $booking->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بعرض هذا الطلب');
        }

        return view('dashboard.bookings.show', compact('booking'));
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,checked_in,in_progress,completed,cancelled',
        ]);

        $booking->update(['status' => $validated['status']]);

        // تحديث التواريخ حسب الحالة
        match($validated['status']) {
            'confirmed' => $booking->update(['confirmed_at' => now()]),
            'checked_in' => $booking->update(['checked_in_at' => now()]),
            'completed' => $booking->update(['completed_at' => now()]),
            'cancelled' => $booking->update(['cancelled_at' => now()]),
            default => null
        };

        Log::info('✅ Booking status updated:', [
            'booking_id' => $booking->id,
            'old_status' => $booking->getOriginal('status'),
            'new_status' => $validated['status'],
        ]);

        return $this->isJsonRequest($request)
            ? response()->json(['success' => true, 'message' => 'تم تحديث الحالة بنجاح'])
            : back()->with('success', 'تم تحديث الحالة بنجاح');
    }

    /**
     * إلغاء الطلب
     */
    public function cancel($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            $isAdmin = Auth::user()->role === 'admin';
            if (!$isAdmin && $booking->user_id !== Auth::id()) {
                abort(403);
            }

            if ($booking->status === 'completed') {
                throw new \Exception('لا يمكن إلغاء طلب مكتمل');
            }

            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            return back()->with('success', 'تم إلغاء الطلب بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * ✅ حذف الحجز
     */
    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            // السماح بالحذف فقط للـ Admin أو صاحب الحجز
            $isAdmin = Auth::user()->role === 'admin';
            if (!$isAdmin && $booking->user_id !== Auth::id()) {
                abort(403, 'غير مصرح لك بحذف هذا الحجز');
            }

            // منع حذف الحجوزات المكتملة
            if ($booking->status === 'completed') {
                return back()->with('error', 'لا يمكن حذف حجز مكتمل');
            }

            $bookingRef = $booking->booking_reference;
            $booking->delete();

            Log::info('🗑️ Booking deleted:', [
                'booking_id' => $id,
                'reference' => $bookingRef,
                'deleted_by' => Auth::id(),
            ]);

            return back()->with('success', 'تم حذف الحجز بنجاح');

        } catch (\Exception $e) {
            Log::error('❌ Error deleting booking: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف الحجز');
        }
    }

    /**
     * طباعة الفاتورة
     */
    public function invoice($id)
    {
        $booking = Booking::with(['user', 'facility', 'room'])->findOrFail($id);

        // التحقق من الصلاحيات
        $isAdmin = Auth::user()->role === 'admin'; // أو is_admin حسب الـ structure عندك
        if (!$isAdmin && $booking->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بعرض هذه الفاتورة');
        }

        return view('dashboard.bookings.invoice', compact('booking'));
    }

    // ============================================
    // Response Helpers
    // ============================================

    private function respondUnauthorized(Request $request)
    {
        if ($this->isJsonRequest($request)) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً',
                'redirect_url' => route('login')
            ], 401);
        }

        return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
    }

    private function respondDoubleSubmit(Request $request)
    {
        if ($this->isJsonRequest($request)) {
            return response()->json([
                'success' => false,
                'message' => 'تم إرسال الطلب مسبقاً، يرجى الانتظار'
            ], 429);
        }

        return back()->with('error', 'تم إرسال الطلب مسبقاً');
    }

    private function respondSuccess(Request $request, Booking $booking)
    {
        if ($this->isJsonRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال الطلب بنجاح',
                'order_number' => $booking->booking_reference,
                'order_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'booking_id' => $booking->id,
                'booking_type' => $booking->booking_type,
            ], 200);
        }

        return redirect()
            ->route('bookings.my-bookings')
            ->with('success', 'تم إرسال الطلب بنجاح! رقم المرجع: ' . $booking->booking_reference);
    }

    private function respondValidationError(Request $request, \Illuminate\Validation\ValidationException $e)
    {
        Log::error('❌ Validation Error', $e->errors());

        if ($this->isJsonRequest($request)) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى التحقق من البيانات المدخلة',
                'errors' => $e->errors(),
            ], 422);
        }

        return back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('error', 'يرجى التحقق من البيانات المدخلة');
    }

    private function respondGeneralError(Request $request, \Exception $e)
    {
        Log::error('❌ General Error: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
        Log::error('Stack Trace: ' . $e->getTraceAsString());

        $errorMessage = config('app.debug')
            ? $e->getMessage()
            : 'حدث خطأ غير متوقع، يرجى المحاولة مرة أخرى';

        if ($this->isJsonRequest($request)) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
            ], 500);
        }

        return back()
            ->withInput()
            ->with('error', $errorMessage);
    }
}
