<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\MenuItem;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    /**
     * عرض كل المطاعم (للعملاء)
     */
    public function index()
    {
        try {
            // ✅ عدل هنا من Restaurant لـ restaurant
            $facilities = Facility::where('type', 'restaurant')
                ->withCount(['bookings' => function($query) {
                    $query->whereIn('status', ['confirmed', 'completed']);
                }])
                ->orderBy('name', 'asc')
                ->get();

            // إحصائيات عامة
            $stats = [
                'total_restaurants' => $facilities->count(),
                'total_orders_today' => Booking::where('booking_type', 'restaurant')
                    ->whereDate('created_at', today())
                    ->count(),
                'featured_restaurant' => $facilities->sortByDesc('bookings_count')->first(),
            ];

            return view('restaurant', compact('facilities', 'stats'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading restaurants: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل المطاعم');
        }
    }

    /**
     * عرض تفاصيل مطعم محدد
     */
    public function show($id)
    {
        try {
            $restaurant = Facility::with(['menuItems'])
            ->withCount(['bookings' => function($query) {
                $query->whereIn('status', ['confirmed', 'completed']);
            }])
            ->findOrFail($id);

            // ✅ عدل هنا
            if ($restaurant->type !== 'restaurant') {
                abort(404, 'المطعم غير موجود');
            }

            // تقييمات (إذا كان موجود)
            $averageRating = $restaurant->bookings()
                ->whereNotNull('rating')
                ->avg('rating');

            // أحدث الطلبات
            $recentOrders = $restaurant->bookings()
                ->where('booking_type', 'restaurant')
                ->latest()
                ->take(5)
                ->get();

            return view('restaurant-details', compact('restaurant', 'averageRating', 'recentOrders'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading restaurant details: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل تفاصيل المطعم');
        }
    }

    /**
     * ✅ Dashboard: عرض كل المطاعم للإدارة
     */
    public function dashboardIndex(Request $request)
    {
        try {
            // ✅ عدل هنا
            $query = Facility::where('type', 'restaurant');

            // البحث
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('city', 'like', "%{$search}%")
                      ->orWhere('manager', 'like', "%{$search}%");
                });
            }

            // فلتر حسب المدينة
            if ($request->filled('city')) {
                $query->where('city', $request->city);
            }

            $restaurants = $query->withCount([
                'bookings',
                'menuItems',
                'bookings as today_orders_count' => function($q) {
                    $q->where('booking_type', 'restaurant')
                      ->whereDate('created_at', today());
                },
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

            // إحصائيات عامة
            $stats = [
                'total' => Facility::where('type', 'restaurant')->count(),
                'total_orders_today' => Booking::where('booking_type', 'restaurant')
                    ->whereDate('created_at', today())
                    ->count(),
                'cities' => Facility::where('type', 'restaurant')
                    ->distinct()
                    ->pluck('city'),
            ];

            return view('dashboard.restaurant', compact('restaurants', 'stats'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading dashboard restaurants: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل المطاعم');
        }
    }

    /**
     * عرض صفحة إضافة مطعم جديد
     */
    public function create()
    {
        $cities = Facility::where('type', 'restaurant')
            ->distinct()
            ->pluck('city')
            ->toArray();

        return view('dashboard.restaurant-create', compact('cities'));
    }

    /**
     * تخزين مطعم جديد
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'address' => 'nullable|string|max:500',
                'capacity' => 'required|integer|min:1',
                'manager' => 'required|string|max:255',
                'contact' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'description' => 'nullable|string|max:1000',
                'opening_hours' => 'nullable|string|max:255',
                'cuisine_type' => 'nullable|string|max:255',
                'delivery_available' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            ]);

            DB::beginTransaction();

            // رفع الصورة
            $imagePath = null;
            if ($request->hasFile('image')) {
                // تأكد إن المجلد موجود
                if (!Storage::disk('public')->exists('restaurants')) {
                    Storage::disk('public')->makeDirectory('restaurants');
                }

                $image = $request->file('image');
                $filename = 'restaurant_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('restaurants', $filename, 'public');

                Log::info('✅ Restaurant image uploaded: ' . $imagePath);
            }

            // إنشاء المطعم - ✅ عدل هنا
            $restaurant = Facility::create([
                'name' => $validated['name'],
                'city' => $validated['city'],
                'address' => $validated['address'] ?? null,
                'capacity' => $validated['capacity'],
                'manager' => $validated['manager'],
                'contact' => $validated['contact'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'description' => $validated['description'] ?? null,
                'opening_hours' => $validated['opening_hours'] ?? null,
                'cuisine_type' => $validated['cuisine_type'] ?? null,
                'delivery_available' => $request->has('delivery_available'),
                'image' => $imagePath,
                'type' => 'restaurant', // ✅ حروف صغيرة
            ]);

            DB::commit();

            Log::info('✅ Restaurant created successfully:', [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'city' => $restaurant->city,
            ]);

            return redirect()
                ->route('dashboard.restaurant')
                ->with('success', 'تم إضافة المطعم بنجاح!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('❌ Validation Error: ', $e->errors());
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'يرجى التحقق من البيانات المدخلة');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error creating restaurant: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة المطعم: ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة تعديل مطعم
     */
    public function edit($id)
    {
        try {
            // ✅ عدل هنا
            $restaurant = Facility::where('type', 'restaurant')->findOrFail($id);

            $cities = Facility::where('type', 'restaurant')
                ->distinct()
                ->pluck('city')
                ->toArray();

            return view('dashboard.restaurant-edit', compact('restaurant', 'cities'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading restaurant for edit: ' . $e->getMessage());
            return back()->with('error', 'المطعم غير موجود');
        }
    }

    /**
     * تحديث بيانات مطعم
     */
    public function update(Request $request, $id)
    {
        try {
            // ✅ عدل هنا
            $restaurant = Facility::where('type', 'restaurant')->findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'address' => 'nullable|string|max:500',
                'capacity' => 'required|integer|min:1',
                'manager' => 'required|string|max:255',
                'contact' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'description' => 'nullable|string|max:1000',
                'opening_hours' => 'nullable|string|max:255',
                'cuisine_type' => 'nullable|string|max:255',
                'delivery_available' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            ]);

            DB::beginTransaction();

            // رفع صورة جديدة
            if ($request->hasFile('image')) {
                // حذف الصورة القديمة
                if ($restaurant->image && Storage::disk('public')->exists($restaurant->image)) {
                    Storage::disk('public')->delete($restaurant->image);
                    Log::info('🗑️ Old image deleted: ' . $restaurant->image);
                }

                $image = $request->file('image');
                $filename = 'restaurant_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $validated['image'] = $image->storeAs('restaurants', $filename, 'public');

                Log::info('✅ New image uploaded: ' . $validated['image']);
            }

            // تحديث البيانات
            $validated['delivery_available'] = $request->has('delivery_available');
            $restaurant->update($validated);

            DB::commit();

            Log::info('✅ Restaurant updated successfully:', [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
            ]);

            return redirect()
                ->route('dashboard.restaurant')
                ->with('success', 'تم تحديث بيانات المطعم بنجاح!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'يرجى التحقق من البيانات المدخلة');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error updating restaurant: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث المطعم');
        }
    }

    /**
     * حذف مطعم
     */
    public function destroy($id)
    {
        try {
            // ✅ عدل هنا
            $restaurant = Facility::where('type', 'restaurant')->findOrFail($id);

            // التحقق من وجود حجوزات نشطة
            $activeBookings = $restaurant->bookings()
                ->whereIn('status', ['pending', 'confirmed'])
                ->count();

            if ($activeBookings > 0) {
                return back()->with('error', 'لا يمكن حذف المطعم لوجود حجوزات نشطة');
            }

            DB::beginTransaction();

            // حذف الصورة
            if ($restaurant->image && Storage::disk('public')->exists($restaurant->image)) {
                Storage::disk('public')->delete($restaurant->image);
                Log::info('🗑️ Restaurant image deleted: ' . $restaurant->image);
            }

            $restaurantName = $restaurant->name;
            $restaurant->delete();

            DB::commit();

            Log::info('✅ Restaurant deleted successfully:', [
                'id' => $id,
                'name' => $restaurantName,
                'deleted_by' => Auth::id(),
            ]);

            return back()->with('success', 'تم حذف المطعم بنجاح!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error deleting restaurant: ' . $e->getMessage());

            return back()->with('error', 'حدث خطأ أثناء حذف المطعم');
        }
    }

    /**
     * ✅ عرض قائمة الطعام لمطعم محدد
     */
    public function menu($id)
    {
        try {
            // ✅ عدل هنا
            $restaurant = Facility::where('type', 'restaurant')->findOrFail($id);

            $menuItems = MenuItem::where('facility_id', $id)
                ->orderBy('category', 'asc')
                ->orderBy('name', 'asc')
                ->get()
                ->groupBy('category');

            return view('restaurant-menu', compact('restaurant', 'menuItems'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading menu: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل القائمة');
        }
    }

    /**
     * ✅ تصدير بيانات المطاعم (Excel/CSV)
     */
    public function export(Request $request)
    {
        try {
            // ✅ عدل هنا
            $restaurants = Facility::where('type', 'restaurant')
                ->withCount('bookings')
                ->get();

            $filename = 'restaurants_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($restaurants) {
                $file = fopen('php://output', 'w');

                // Headers
                fputcsv($file, [
                    'ID', 'Name', 'City', 'Manager', 'Contact',
                    'Capacity', 'Total Orders', 'Created At'
                ]);

                // Data
                foreach ($restaurants as $restaurant) {
                    fputcsv($file, [
                        $restaurant->id,
                        $restaurant->name,
                        $restaurant->city,
                        $restaurant->manager,
                        $restaurant->contact,
                        $restaurant->capacity,
                        $restaurant->bookings_count,
                        $restaurant->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('❌ Error exporting restaurants: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء التصدير');
        }
    }
}
