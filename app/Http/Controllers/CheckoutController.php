<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function showForm($facility_id)
    {
        return view('checkout', ['facility_id' => $facility_id]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|integer',
            'booking_type' => 'required|string',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'nullable|email',
            'guest_phone' => 'required|string|max:20',
            'check_in_date' => 'nullable|date',
            'check_out_date' => 'nullable|date',
            'reservation_date' => 'nullable|date',
            'reservation_time' => 'nullable',
            'number_of_guests' => 'nullable|integer',
            'total_price' => 'nullable|numeric',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['payment_status'] = 'paid'; // أو 'unpaid' إذا لم يتم الدفع بعد

        Booking::create($validated);

        return redirect()->route('dashboard.bookings.index')
                         ->with('success', 'Booking created successfully!');
    }
}
