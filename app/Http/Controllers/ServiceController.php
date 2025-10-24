<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index(Request $request)
    {
        try {
            $query = Service::with('facility');

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('facility', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Filter by facility
            if ($request->filled('facility_id')) {
                $query->where('facility_id', $request->facility_id);
            }

            // Filter by price range
            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            // Sort
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $services = $query->paginate(15)->withQueryString();

            // Statistics
            $stats = [
                'total_services' => Service::count(),
                'total_facilities' => Facility::has('services')->count(),
                'avg_price' => Service::avg('price') ?? 0,
                'services_with_price' => Service::whereNotNull('price')->count(),
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $services,
                    'stats' => $stats
                ]);
            }

            $facilities = Facility::orderBy('name')->get();

            return view('dashboard.service.index', compact('services', 'stats', 'facilities'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading services: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading services'
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء تحميل الخدمات');
        }
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        try {
            $facilities = Facility::orderBy('name')->get();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $facilities
                ]);
            }

            return view('dashboard.service.create', compact('facilities'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading create form: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading facilities'
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء تحميل النموذج');
        }
    }

    /**
     * Store a newly created service in storage
     */
    public function store(Request $request)
    {
        Log::info('========== NEW SERVICE REQUEST ==========');
        Log::info('Request Data:', $request->all());

        try {
            $validated = $request->validate([
                'facility_id' => 'required|exists:facilities,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'nullable|numeric|min:0|max:999999.99',
            ]);

            DB::beginTransaction();

            $service = Service::create($validated);

            DB::commit();

            Log::info('✅ Service created successfully:', [
                'id' => $service->id,
                'name' => $service->name,
                'facility_id' => $service->facility_id,
                'created_by' => Auth::id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service added successfully!',
                    'data' => $service->load('facility')
                ], 201);
            }

            return redirect()
                ->route('service.index')
                ->with('success', 'تم إضافة الخدمة بنجاح! ✅');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('❌ Validation Error:', $e->errors());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'يرجى التحقق من البيانات المدخلة');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error creating service: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating service: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة الخدمة: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified service
     */
    public function show(Service $service)
    {
        try {
            $service->load('facility');

            // Get related statistics
            $stats = [
                'total_bookings' => $service->facility->bookings()->count(),
                'facility_services_count' => $service->facility->services()->count(),
            ];

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $service,
                    'stats' => $stats
                ]);
            }

            return view('dashboard.service.show', compact('service', 'stats'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading service: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found'
                ], 404);
            }

            return back()->with('error', 'الخدمة غير موجودة');
        }
    }

    /**
     * Show the form for editing the specified service
     */
    public function edit(Service $service)
    {
        try {
            $facilities = Facility::orderBy('name')->get();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'service' => $service,
                    'facilities' => $facilities
                ]);
            }

            return view('dashboard.service.edit', compact('service', 'facilities'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading edit form: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading service'
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء تحميل بيانات الخدمة');
        }
    }

    /**
     * Update the specified service in storage
     */
    public function update(Request $request, Service $service)
    {
        Log::info('========== UPDATE SERVICE REQUEST ==========');
        Log::info('Service ID: ' . $service->id);
        Log::info('Request Data:', $request->all());

        try {
            $validated = $request->validate([
                'facility_id' => 'required|exists:facilities,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'nullable|numeric|min:0|max:999999.99',
            ]);

            DB::beginTransaction();

            $service->update($validated);

            DB::commit();

            Log::info('✅ Service updated successfully:', [
                'id' => $service->id,
                'name' => $service->name,
                'updated_by' => Auth::id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service updated successfully!',
                    'data' => $service->load('facility')
                ]);
            }

            return redirect()
                ->route('service.index')
                ->with('success', 'تم تحديث الخدمة بنجاح! ✅');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('❌ Validation Error:', $e->errors());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'يرجى التحقق من البيانات المدخلة');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error updating service: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating service'
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الخدمة');
        }
    }

    /**
     * Remove the specified service from storage
     */
    public function destroy(Service $service)
    {
        try {
            DB::beginTransaction();

            $serviceName = $service->name;
            $serviceId = $service->id;

            $service->delete();

            DB::commit();

            Log::info('✅ Service deleted successfully:', [
                'id' => $serviceId,
                'name' => $serviceName,
                'deleted_by' => Auth::id(),
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service deleted successfully!'
                ]);
            }

            return redirect()
                ->route('service.index')
                ->with('success', 'تم حذف الخدمة بنجاح! ✅');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error deleting service: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting service'
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء حذف الخدمة');
        }
    }

    /**
     * Get services by facility (API endpoint)
     */
    public function getByFacility($facilityId)
    {
        try {
            $facility = Facility::findOrFail($facilityId);

            $services = Service::where('facility_id', $facilityId)
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $services,
                'facility' => [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'type' => $facility->type,
                ],
                'count' => $services->count()
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error getting services by facility: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving services'
            ], 500);
        }
    }

    /**
     * Get all services as JSON
     */
    public function getAll(Request $request)
    {
        try {
            $query = Service::with('facility');

            // Optional filters
            if ($request->filled('facility_type')) {
                $query->whereHas('facility', function($q) use ($request) {
                    $q->where('type', $request->facility_type);
                });
            }

            if ($request->filled('has_price')) {
                if ($request->has_price == 'yes') {
                    $query->whereNotNull('price');
                } else {
                    $query->whereNull('price');
                }
            }

            $services = $query->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'data' => $services,
                'count' => $services->count()
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error getting all services: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving services'
            ], 500);
        }
    }

    /**
     * Bulk delete services
     */
    public function bulkDelete(Request $request)
    {
        try {
            $validated = $request->validate([
                'service_ids' => 'required|array',
                'service_ids.*' => 'required|exists:services,id',
            ]);

            DB::beginTransaction();

            $deletedCount = Service::whereIn('id', $validated['service_ids'])->delete();

            DB::commit();

            Log::info("✅ Bulk delete services: {$deletedCount} services deleted by user " . Auth::id());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "{$deletedCount} services deleted successfully!"
                ]);
            }

            return back()->with('success', "تم حذف {$deletedCount} خدمة بنجاح! ✅");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error bulk deleting services: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting services'
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء حذف الخدمات');
        }
    }

    /**
     * Export services to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = Service::with('facility');

            if ($request->filled('facility_id')) {
                $query->where('facility_id', $request->facility_id);
            }

            $services = $query->orderBy('name')->get();

            $filename = 'services_export_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($services) {
                $file = fopen('php://output', 'w');

                // Headers
                fputcsv($file, [
                    'ID',
                    'Service Name',
                    'Facility Name',
                    'Facility Type',
                    'Description',
                    'Price',
                    'Created At',
                ]);

                // Data
                foreach ($services as $service) {
                    fputcsv($file, [
                        $service->id,
                        $service->name,
                        $service->facility->name ?? 'N/A',
                        $service->facility->type ?? 'N/A',
                        $service->description ?? 'N/A',
                        $service->price ? '$' . number_format($service->price, 2) : 'Free',
                        $service->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            Log::info('✅ Services exported by user ' . Auth::id());

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('❌ Error exporting services: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء التصدير');
        }
    }
}
