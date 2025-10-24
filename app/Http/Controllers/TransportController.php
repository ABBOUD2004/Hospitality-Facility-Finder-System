<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TransportController extends Controller
{
    /**
     * Display transportation management page
     */
    public function index(Request $request)
    {
        // Build query with filters
        $query = Transport::with(['user', 'driver', 'booking']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by vehicle type
        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('pickup_date', $request->date);
        }

        // Search by customer name or phone
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%');
            });
        }

        // Get paginated transports
        $transports = $query->latest()->paginate(20);

        // Calculate statistics
        $stats = [
            'active_rides' => Transport::where('status', 'active')->count(),
            'completed_today' => Transport::where('status', 'completed')
                ->whereDate('created_at', Carbon::today())
                ->count(),
            'pending' => Transport::where('status', 'pending')->count(),
            'revenue' => Transport::where('status', 'completed')
                ->whereDate('created_at', Carbon::today())
                ->sum('price'),
            'total_rides' => Transport::count(),
            'cancelled' => Transport::where('status', 'cancelled')->count(),
        ];

        return view('transport', compact('transports', 'stats'));
    }

    /**
     * Create transport from booking
     */
    public function createFromBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->booking_type !== 'transport') {
            return redirect()->back()->with('error', 'Invalid booking type');
        }

        return view('transport-create', compact('booking'));
    }

    /**
     * Show the form for creating a new transport booking
     */
    public function create()
    {
        return view('transport-create');
    }

    /**
     * Store a newly created transport booking
     * ✅ الكود الصحيح للنقل
     */
    public function store(Request $request)
    {
        Log::info('========== NEW TRANSPORT BOOKING ==========');
        Log::info('Request Data:', $request->all());

        try {
            // ✅ Validation الصحيح للنقل
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'nullable|email|max:255',
                'phone' => 'required|string|max:20',
                'pickup_location' => 'required|string|max:255',
                'drop_location' => 'required|string|max:255',
                'pickup_date' => 'required|date|after_or_equal:today',
                'pickup_time' => 'required',
                'vehicle_type' => 'required|in:car,van,truck,bus',
                'passengers' => 'nullable|integer|min:1|max:50',
                'distance' => 'nullable|string|max:50',
                'price' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:1000',
                'booking_id' => 'nullable|exists:bookings,id'
            ]);

            DB::beginTransaction();

            // إضافة بيانات إضافية
            $validated['status'] = 'pending';
            $validated['user_id'] = auth()->id();

            // إنشاء حجز النقل
            $transport = Transport::create($validated);

            Log::info('✅ Transport created with ID: ' . $transport->id);

            // تحديث حالة الـ Booking لو موجود
            if ($request->booking_id) {
                Booking::find($request->booking_id)->update([
                    'status' => 'confirmed',
                    'confirmed_at' => now()
                ]);
                Log::info('✅ Booking updated: ' . $request->booking_id);
            }

            DB::commit();

            // استجابة AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transport booking created successfully',
                    'transport_id' => $transport->id
                ]);
            }

            return redirect()
                ->route('transport.index')
                ->with('success', 'Transport booking created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('❌ Validation Error:', $e->errors());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Please fix the validation errors'
                ], 422);
            }

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Please check the form data');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error creating transport: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating transport booking: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Error creating transport: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified transport booking
     */
    public function show($id)
    {
        $transport = Transport::with(['user', 'driver'])->findOrFail($id);
        return view('transport-show', compact('transport'));
    }

    /**
     * Show the form for editing the transport booking
     */
    public function edit($id)
    {
        $transport = Transport::findOrFail($id);
        return view('transport-edit', compact('transport'));
    }

    /**
     * Update the specified transport booking
     */
    public function update(Request $request, $id)
    {
        try {
            $transport = Transport::findOrFail($id);

            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'nullable|email|max:255',
                'phone' => 'required|string|max:20',
                'pickup_location' => 'required|string|max:255',
                'drop_location' => 'required|string|max:255',
                'pickup_date' => 'required|date',
                'pickup_time' => 'required',
                'vehicle_type' => 'required|in:car,van,truck,bus',
                'passengers' => 'nullable|integer|min:1|max:50',
                'distance' => 'nullable|string|max:50',
                'price' => 'required|numeric|min:0',
                'status' => 'required|in:pending,active,completed,cancelled',
                'notes' => 'nullable|string|max:1000',
            ]);

            $transport->update($validated);

            Log::info("✅ Transport updated: {$transport->id}");

            return redirect()
                ->route('transport.index')
                ->with('success', 'Transport booking updated successfully!');

        } catch (\Exception $e) {
            Log::error('❌ Error updating transport: ' . $e->getMessage());
            return back()->with('error', 'Error updating transport');
        }
    }

    /**
     * Remove the specified transport booking
     */
    public function destroy($id)
    {
        try {
            $transport = Transport::findOrFail($id);
            $transport->delete();

            Log::info("✅ Transport deleted: {$id}");

            return redirect()
                ->route('transport.index')
                ->with('success', 'Transport booking deleted successfully!');

        } catch (\Exception $e) {
            Log::error('❌ Error deleting transport: ' . $e->getMessage());
            return back()->with('error', 'Error deleting transport');
        }
    }

    /**
     * Assign driver to transport
     */
    public function assignDriver(Request $request, $id)
    {
        try {
            $transport = Transport::findOrFail($id);

            $validated = $request->validate([
                'driver_id' => 'required|exists:users,id',
            ]);

            $transport->update([
                'driver_id' => $validated['driver_id'],
                'status' => 'active'
            ]);

            Log::info("✅ Driver assigned to transport {$id}");

            return redirect()
                ->route('transport.index')
                ->with('success', 'Driver assigned successfully!');

        } catch (\Exception $e) {
            Log::error('❌ Error assigning driver: ' . $e->getMessage());
            return back()->with('error', 'Error assigning driver');
        }
    }

    /**
     * Start the trip
     */
    public function startTrip($id)
    {
        try {
            $transport = Transport::findOrFail($id);

            if ($transport->status !== 'pending') {
                return redirect()->back()->with('error', 'Only pending trips can be started.');
            }

            $transport->update([
                'status' => 'active',
                'started_at' => Carbon::now()
            ]);

            Log::info("✅ Trip started: {$id}");

            return redirect()
                ->route('transport.index')
                ->with('success', 'Trip started successfully!');

        } catch (\Exception $e) {
            Log::error('❌ Error starting trip: ' . $e->getMessage());
            return back()->with('error', 'Error starting trip');
        }
    }

    /**
     * Complete the trip
     */
    public function completeTrip($id)
    {
        try {
            $transport = Transport::findOrFail($id);

            if ($transport->status !== 'active') {
                return redirect()->back()->with('error', 'Only active trips can be completed.');
            }

            $transport->update([
                'status' => 'completed',
                'completed_at' => Carbon::now()
            ]);

            Log::info("✅ Trip completed: {$id}");

            return redirect()
                ->route('transport.index')
                ->with('success', 'Trip completed successfully!');

        } catch (\Exception $e) {
            Log::error('❌ Error completing trip: ' . $e->getMessage());
            return back()->with('error', 'Error completing trip');
        }
    }

    /**
     * Cancel the trip
     */
    public function cancelTrip($id)
    {
        try {
            $transport = Transport::findOrFail($id);

            if (in_array($transport->status, ['completed', 'cancelled'])) {
                return redirect()->back()->with('error', 'This trip cannot be cancelled.');
            }

            $transport->update([
                'status' => 'cancelled',
                'cancelled_at' => Carbon::now()
            ]);

            Log::info("✅ Trip cancelled: {$id}");

            return redirect()
                ->route('transport.index')
                ->with('success', 'Trip cancelled successfully!');

        } catch (\Exception $e) {
            Log::error('❌ Error cancelling trip: ' . $e->getMessage());
            return back()->with('error', 'Error cancelling trip');
        }
    }

    /**
     * Track transport location
     */
    public function track($id)
    {
        $transport = Transport::with(['driver'])->findOrFail($id);
        return view('transport-track', compact('transport'));
    }

    /**
     * Export transports to CSV
     */
    public function export(Request $request)
    {
        $query = Transport::with(['user', 'driver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        if ($request->filled('date')) {
            $query->whereDate('pickup_date', $request->date);
        }

        $transports = $query->latest()->get();

        $filename = 'transports_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transports) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID',
                'Customer Name',
                'Email',
                'Phone',
                'Pickup Location',
                'Drop Location',
                'Distance',
                'Vehicle Type',
                'Passengers',
                'Price',
                'Status',
                'Pickup Date',
                'Pickup Time',
                'Driver',
            ]);

            foreach ($transports as $transport) {
                fputcsv($file, [
                    'TR-' . str_pad($transport->id, 4, '0', STR_PAD_LEFT),
                    $transport->customer_name,
                    $transport->customer_email ?? 'N/A',
                    $transport->phone,
                    $transport->pickup_location,
                    $transport->drop_location,
                    $transport->distance ?? 'N/A',
                    ucfirst($transport->vehicle_type),
                    $transport->passengers ?? 'N/A',
                    '$' . number_format($transport->price, 2),
                    ucfirst($transport->status),
                    Carbon::parse($transport->pickup_date)->format('M d, Y'),
                    $transport->pickup_time,
                    $transport->driver->name ?? 'Not Assigned',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Update transport status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $transport = Transport::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:pending,active,completed,cancelled'
            ]);

            $transport->update(['status' => $validated['status']]);

            // Sync with booking if exists
            if ($transport->booking_id) {
                $bookingStatus = match($validated['status']) {
                    'active' => 'confirmed',
                    'completed' => 'completed',
                    'cancelled' => 'cancelled',
                    default => 'pending'
                };

                Booking::find($transport->booking_id)->update([
                    'status' => $bookingStatus
                ]);
            }

            Log::info("✅ Transport status updated: {$id} -> {$validated['status']}");

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error updating status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating status'
            ], 500);
        }
    }

}
