<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomController extends Controller
{
    /**
     * عرض صفحة حجز الغرفة
     */
    public function book($id)
    {
        $room = Room::with('facility')->findOrFail($id);
        return view('rooms.book', compact('room'));
    }

    /**
     * معالجة عملية الحجز
     */
    public function processBooking(Request $request)
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to make a booking');
        }

        // التحقق من البيانات
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'facility_id' => 'required|exists:facilities,id',
            'guest_firstname' => 'required|string|max:255',
            'guest_lastname' => 'required|string|max:255',
            'guest_email' => 'required|email',
            'guest_phone' => 'required|string',
            'checkin_date' => 'required|date|after_or_equal:today',
            'checkout_date' => 'required|date|after:checkin_date',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:10',
            'nights' => 'required|integer|min:1',
            'total_price_rwf' => 'required|numeric',
            'total_price_usd' => 'required|numeric',
            'payment_method' => 'required|in:mtn,visa',
            'mtn_number' => 'required_if:payment_method,mtn|nullable|string',
        ]);

        // جلب بيانات الغرفة
        $room = Room::with('facility')->findOrFail($validated['room_id']);

        // التحقق من توفر الغرفة
        if ($room->availability <= 0) {
            return back()->with('error', 'Sorry, this room is not available')->withInput();
        }

        // التحقق من الحجوزات المتداخلة
        $conflictingBookings = Booking::where('room_id', $room->id)
            ->whereNotIn('status', ['cancelled'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('checkin_date', [$validated['checkin_date'], $validated['checkout_date']])
                    ->orWhereBetween('checkout_date', [$validated['checkin_date'], $validated['checkout_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('checkin_date', '<=', $validated['checkin_date'])
                          ->where('checkout_date', '>=', $validated['checkout_date']);
                    });
            })
            ->count();

        if ($conflictingBookings >= $room->availability) {
            return back()->with('error', 'This room is not available for the selected dates')->withInput();
        }

        try {
            DB::beginTransaction();

            // إعداد بيانات الدفع
            $paymentDetails = null;
            if ($validated['payment_method'] === 'mtn') {
                $paymentDetails = ['mtn_number' => $validated['mtn_number']];
            }

            // إنشاء الحجز
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'room_id' => $room->id,
                'facility_id' => $validated['facility_id'],
                'booking_type' => 'hotel',
                'guest_firstname' => $validated['guest_firstname'],
                'guest_lastname' => $validated['guest_lastname'],
                'guest_email' => $validated['guest_email'],
                'guest_phone' => $validated['guest_phone'],
                'checkin_date' => $validated['checkin_date'],
                'checkout_date' => $validated['checkout_date'],
                'adults' => $validated['adults'],
                'children' => $validated['children'] ?? 0,
                'nights' => $validated['nights'],
                'total_price_usd' => $validated['total_price_usd'],
                'total_price_rwf' => $validated['total_price_rwf'],
                'total_price' => $validated['total_price_rwf'],
                'payment_method' => $validated['payment_method'],
                'payment_details' => $paymentDetails,
                'payment_phone' => $validated['mtn_number'] ?? null,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // تقليل عدد الغرف المتاحة
            $room->decrement('availability');

            DB::commit();

            // إرسال بريد إلكتروني للتأكيد (اختياري)
            // $booking->sendConfirmationEmail();

            return redirect()
                ->route('bookings.success', $booking->id)
                ->with('success', 'Booking confirmed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Booking Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Booking failed. Please try again.');
        }
    }

    /**
     * عرض صفحة نجاح الحجز
     */
    public function bookingSuccess($id)
    {
        $booking = Booking::with(['room.facility', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('bookings.success', compact('booking'));
    }

    /**
     * عرض تفاصيل الحجز
     */
    public function showBooking($id)
    {
        $booking = Booking::with(['room.facility', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('bookings.show', compact('booking'));
    }

    /**
     * عرض جميع حجوزات المستخدم
     */
    public function myBookings()
    {
        $bookings = Booking::with(['room.facility'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * إلغاء الحجز
     */
    public function cancelBooking($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // التحقق من إمكانية الإلغاء
        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'Cannot cancel this booking. It may be too close to check-in date or already completed.');
        }

        try {
            DB::beginTransaction();

            // إلغاء الحجز وإرجاع الغرفة
            $booking->cancel();

            DB::commit();

            // إرسال بريد الإلغاء
            // $booking->sendCancellationEmail();

            return back()->with('success', 'Booking cancelled successfully. Refund will be processed within 3-5 business days.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel booking. Please try again or contact support.');
        }
    }

    /**
     * تحديث حالة الدفع (Admin only)
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'transaction_id' => 'nullable|string',
        ]);

        try {
            if ($validated['payment_status'] === 'paid') {
                $booking->markAsPaid($validated['transaction_id'] ?? null);
            } else {
                $booking->update([
                    'payment_status' => $validated['payment_status'],
                    'transaction_id' => $validated['transaction_id'] ?? null,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully',
                'booking' => $booking,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status',
            ], 500);
        }
    }

    /**
     * التحقق من توفر الغرفة
     */
    public function checkAvailability(Request $request, $id)
    {
        $validated = $request->validate([
            'checkin_date' => 'required|date|after_or_equal:today',
            'checkout_date' => 'required|date|after:checkin_date',
        ]);

        $room = Room::findOrFail($id);

        // عدد الحجوزات المتداخلة
        $conflictingBookings = Booking::where('room_id', $room->id)
            ->whereNotIn('status', ['cancelled'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('checkin_date', [$validated['checkin_date'], $validated['checkout_date']])
                    ->orWhereBetween('checkout_date', [$validated['checkin_date'], $validated['checkout_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('checkin_date', '<=', $validated['checkin_date'])
                          ->where('checkout_date', '>=', $validated['checkout_date']);
                    });
            })
            ->count();

        $available = ($room->availability - $conflictingBookings) > 0;
        $remaining = max(0, $room->availability - $conflictingBookings);

        return response()->json([
            'available' => $available,
            'remaining' => $remaining,
            'message' => $available ? "✓ {$remaining} room(s) available" : '✗ Room is not available for selected dates',
        ]);
    }

    /**
     * حساب السعر الإجمالي
     */
    public function calculatePrice(Request $request, $id)
    {
        $validated = $request->validate([
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
        ]);

        $room = Room::findOrFail($id);

        $checkin = Carbon::parse($validated['checkin_date']);
        $checkout = Carbon::parse($validated['checkout_date']);
        $nights = $checkout->diffInDays($checkin);

        $pricePerNightUsd = $room->price_usd ?? 0;
        $pricePerNightRwf = $room->price_rwf ?? ($pricePerNightUsd * 1300);

        $totalUSD = $pricePerNightUsd * $nights;
        $totalRWF = $pricePerNightRwf * $nights;

        return response()->json([
            'nights' => $nights,
            'price_per_night_usd' => $pricePerNightUsd,
            'price_per_night_rwf' => $pricePerNightRwf,
            'total_usd' => $totalUSD,
            'total_rwf' => $totalRWF,
            'formatted' => [
                'total_usd' => '$' . number_format($totalUSD, 2),
                'total_rwf' => number_format($totalRWF, 0) . ' RWF',
            ],
        ]);
    }

    /**
     * فلترة الغرف حسب الفئة
     */
    public function filterByCategory($facilityId, $category = 'all')
    {
        $facility = Facility::with(['rooms' => function ($query) use ($category) {
            if ($category !== 'all') {
                $query->where('category', $category);
            }
            $query->where('availability', '>', 0);
        }])->findOrFail($facilityId);

        return view('facilities.show', compact('facility'));
    }
}
