<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // عرض جميع الحجوزات في الداشبورد
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'facility', 'room'])
            ->orderBy('created_at', 'desc');

        // فلترة حسب النوع
        if ($request->has('type') && $request->type != 'all') {
            $query->where('booking_type', $request->type);
        }

        // فلترة حسب الحالة
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // فلترة حسب حالة الدفع
        if ($request->has('payment_status') && $request->payment_status != 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_phone', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(20);

        // إحصائيات
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'payment_pending' => Booking::where('payment_status', 'pending')->count(),
            'revenue_today' => Booking::whereDate('created_at', today())
                ->where('payment_status', 'paid')
                ->sum('total_price'),
            'revenue_month' => Booking::whereMonth('created_at', now()->month)
                ->where('payment_status', 'paid')
                ->sum('total_price'),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    // عرض تفاصيل الحجز
    public function show(Booking $booking)
    {
        $booking->load(['user', 'facility', 'room']);
        return view('admin.bookings.show', compact('booking'));
    }

    // تأكيد الحجز
    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'لا يمكن تأكيد هذا الحجز');
        }

        $booking->confirm();

        // يمكن إضافة إرسال إشعار للعميل هنا
        // event(new BookingConfirmed($booking));

        return back()->with('success', 'تم تأكيد الحجز بنجاح');
    }

    // إكمال الحجز
    public function complete(Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'لا يمكن إكمال هذا الحجز');
        }

        $booking->complete();

        return back()->with('success', 'تم إكمال الحجز بنجاح');
    }

    // إلغاء الحجز
    public function cancel(Booking $booking)
    {
        if ($booking->status === 'completed') {
            return back()->with('error', 'لا يمكن إلغاء حجز مكتمل');
        }

        $booking->cancel();

        return back()->with('success', 'تم إلغاء الحجز بنجاح');
    }

    // تحديث حالة الدفع
    public function updatePaymentStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $booking->update([
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'تم تحديث حالة الدفع بنجاح');
    }

    // إضافة ملاحظات للأدمن
    public function addNote(Request $request, Booking $booking)
    {
        $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        $existingNotes = $booking->admin_notes ?? '';
        $newNote = "\n[" . now()->format('Y-m-d H:i') . "] " . auth()->user()->name . ": " . $request->note;

        $booking->update([
            'admin_notes' => $existingNotes . $newNote,
        ]);

        return back()->with('success', 'تم إضافة الملاحظة بنجاح');
    }

    // تحديث معلومات الحجز
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'nullable|email',
            'guest_phone' => 'required|string|max:20',
            'check_in_date' => 'nullable|date',
            'check_out_date' => 'nullable|date|after:check_in_date',
            'reservation_date' => 'nullable|date',
            'reservation_time' => 'nullable|date_format:H:i',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'number_of_guests' => 'nullable|integer|min:1',
            'special_requests' => 'nullable|string',
            'total_price' => 'nullable|numeric|min:0',
        ]);

        $booking->update($validated);

        return back()->with('success', 'تم تحديث بيانات الحجز بنجاح');
    }

    // حذف الحجز
    public function destroy(Booking $booking)
    {
        $reference = $booking->booking_reference;
        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', "تم حذف الحجز {$reference} بنجاح");
    }

    // تصدير الحجوزات
    public function export(Request $request)
    {
        $bookings = Booking::with(['user', 'facility', 'room'])
            ->when($request->type, fn($q) => $q->where('booking_type', $request->type))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'bookings_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'Reference',
                'Type',
                'Guest Name',
                'Phone',
                'Email',
                'Facility',
                'Date',
                'Status',
                'Payment Status',
                'Total Price',
            ]);

            // Data
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_reference,
                    $booking->booking_type,
                    $booking->guest_name,
                    $booking->guest_phone,
                    $booking->guest_email,
                    $booking->facility->name ?? 'N/A',
                    $booking->check_in_date ?? $booking->reservation_date,
                    $booking->status,
                    $booking->payment_status,
                    $booking->total_price,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
