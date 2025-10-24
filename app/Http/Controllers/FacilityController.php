<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Service;
use App\Models\Gallery;
use App\Models\Room;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FacilityController extends Controller
{
    // ========================= HOME PAGE =========================
    public function index(Request $request)
    {
        try {
            $type = $request->get('type', 'all');
            $query = Facility::query();

            if ($type !== 'all') {
                $query->where('type', $type);
            }

            $facilities = $query->latest()->paginate(9);
            $facilities->appends(['type' => $type]);

            return view('home', compact('facilities', 'type'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading home: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل الصفحة');
        }
    }

    // ========================= عرض صفحات الفنادق العامة =========================
    public function showHotels()
    {
        $facilities = Facility::where('type', 'hotel')
            ->with('rooms')
            ->latest()
            ->get();

        return view('hotels', compact('facilities'));
    }

    public function showRestaurants()
    {
        $facilities = Facility::where('type', 'restaurant')
            ->latest()
            ->get();

        return view('restaurants', compact('facilities'));
    }

    public function showCoffeeShops()
    {
        $facilities = Facility::where('type', 'coffee_shop')
            ->latest()
            ->get();

        return view('coffee-shops', compact('facilities'));
    }

    // ========================= عرض تفاصيل منشأة معينة =========================
    public function show($id)
    {
        try {
            $facility = Facility::with(['rooms', 'gallery', 'services', 'menuItems'])
                ->findOrFail($id);

            $viewMap = [
                'hotel' => 'facility.hotel-show',
                'coffee_shop' => 'facility.coffee-show',
                'coffee shop' => 'facility.coffee-show',
                'restaurant' => 'facility.restaurant-show',
            ];

            $view = $viewMap[strtolower($facility->type)] ?? null;

            if (!$view) {
                return redirect()->route('home')->with('error', 'Facility type not found');
            }

            return view($view, compact('facility'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading facility: ' . $e->getMessage());
            return back()->with('error', 'المنشأة غير موجودة');
        }
    }

    public function showHotel($id)
    {
        $facility = Facility::where('type', 'hotel')
            ->with(['rooms', 'gallery', 'services'])
            ->findOrFail($id);

        return view('facility.hotel-show', compact('facility'));
    }

    public function showCoffeeShop($id)
    {
        $facility = Facility::where('type', 'coffee_shop')
            ->with(['gallery', 'services', 'menuItems'])
            ->findOrFail($id);

        return view('facility.coffee-show', compact('facility'));
    }

    public function showRestaurant($id)
    {
        $facility = Facility::where('type', 'restaurant')
            ->with(['gallery', 'services', 'menuItems'])
            ->findOrFail($id);

        return view('facility.restaurant-show', compact('facility'));
    }

    // ========================= RESTAURANT =========================
    public function createRestaurant()
    {
        $facilities = Facility::where('type', 'restaurant')
            ->with(['gallery', 'services', 'menuItems'])
            ->latest()
            ->get();

        return view('restaurant', compact('facilities'));
    }

    public function storeRestaurant(Request $request)
    {
        Log::info('========== NEW RESTAURANT REQUEST ==========');
        Log::info('📥 Request Data:', $request->all());

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'address' => 'nullable|string|max:500',
                'capacity' => 'nullable|integer|min:1',
                'manager' => 'required|string|max:255',
                'contact' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'description' => 'nullable|string|max:1000',
                'opening_hours' => 'nullable|string|max:255',
                'cuisine_type' => 'nullable|string|max:255',
                'delivery_available' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
                'services' => 'nullable|array',
                'services.*' => 'nullable|string|max:255',
                'gallery' => 'nullable|array',
                'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
            ]);

            Log::info('✅ Validation Passed');

            DB::beginTransaction();

            $facility = new Facility();
            $facility->name = $validated['name'];
            $facility->city = $validated['city'];
            $facility->address = $validated['address'] ?? null;
            $facility->capacity = $validated['capacity'] ?? null;
            $facility->manager = $validated['manager'];
            $facility->contact = $validated['contact'];
            $facility->email = $validated['email'] ?? null;
            $facility->phone = $validated['phone'] ?? null;
            $facility->description = $validated['description'] ?? null;
            $facility->opening_hours = $validated['opening_hours'] ?? null;
            $facility->cuisine_type = $validated['cuisine_type'] ?? null;
            $facility->delivery_available = $request->has('delivery_available');
            $facility->type = 'restaurant';

            // ✅ حفظ الصورة في public/images مباشرة (زي HotelController)
            if ($request->hasFile('image')) {
                $restaurantFolder = public_path('images/restaurants');
                if (!file_exists($restaurantFolder)) {
                    mkdir($restaurantFolder, 0755, true);
                }

                $image = $request->file('image');
                $imageName = 'restaurant_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($restaurantFolder, $imageName);
                $facility->image = 'images/restaurants/' . $imageName;

                Log::info('✅ Image uploaded: images/restaurants/' . $imageName);
            }

            $facility->save();

            Log::info('✅ Facility created with ID: ' . $facility->id);

            // ✅ حفظ الخدمات
            if ($request->has('services') && is_array($request->services)) {
                foreach ($request->services as $serviceName) {
                    if (!empty(trim($serviceName))) {
                        Service::create([
                            'facility_id' => $facility->id,
                            'name' => trim($serviceName),
                        ]);
                    }
                }
            }

            // ✅ حفظ معرض الصور في public/images
            if ($request->hasFile('gallery')) {
                $galleryFolder = public_path('images/gallery');
                if (!file_exists($galleryFolder)) {
                    mkdir($galleryFolder, 0755, true);
                }

                foreach ($request->file('gallery') as $index => $galleryImage) {
                    $galleryName = 'gallery_' . time() . '_' . $index . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                    $galleryImage->move($galleryFolder, $galleryName);

                    Gallery::create([
                        'facility_id' => $facility->id,
                        'image' => 'images/gallery/' . $galleryName,
                    ]);

                    Log::info('✅ Gallery image added: images/gallery/' . $galleryName);
                }
            }

            DB::commit();

            Log::info('✅✅✅ Restaurant created successfully! ID: ' . $facility->id);

            return redirect()
                ->route('dashboard.restaurant')
                ->with('success', 'تم إضافة المطعم بنجاح! 🎉');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('❌ Validation Error:', $e->errors());

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'يرجى التحقق من البيانات المدخلة');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error creating restaurant: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    // ========================= EDIT RESTAURANT =========================
    public function editRestaurant($id)
    {
        try {
            $facility = Facility::where('type', 'restaurant')
                ->with(['gallery', 'services', 'menuItems'])
                ->findOrFail($id);

            return view('dashboard.restaurant-edit', compact('facility'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading restaurant: ' . $e->getMessage());
            return back()->with('error', 'المطعم غير موجود');
        }
    }

    // ========================= UPDATE RESTAURANT =========================
    public function updateRestaurant(Request $request, $id)
    {
        try {
            $facility = Facility::where('type', 'restaurant')->findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'address' => 'nullable|string|max:500',
                'capacity' => 'nullable|integer|min:1',
                'manager' => 'required|string|max:255',
                'contact' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'description' => 'nullable|string|max:1000',
                'opening_hours' => 'nullable|string|max:255',
                'cuisine_type' => 'nullable|string|max:255',
                'delivery_available' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
            ]);

            DB::beginTransaction();

            $facility->fill($validated);
            $facility->delivery_available = $request->has('delivery_available');

            // تحديث الصورة
            if ($request->hasFile('image')) {
                // حذف الصورة القديمة
                if ($facility->image && file_exists(public_path($facility->image))) {
                    unlink(public_path($facility->image));
                }

                $restaurantFolder = public_path('images/restaurants');
                if (!file_exists($restaurantFolder)) {
                    mkdir($restaurantFolder, 0755, true);
                }

                $image = $request->file('image');
                $imageName = 'restaurant_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($restaurantFolder, $imageName);
                $facility->image = 'images/restaurants/' . $imageName;
            }

            $facility->save();

            DB::commit();

            Log::info("✅ Restaurant updated: {$facility->name} (ID: {$id})");

            return redirect()
                ->route('dashboard.restaurant')
                ->with('success', 'تم تحديث المطعم بنجاح! ✅');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error updating restaurant: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء التحديث');
        }
    }

    // ========================= COFFEE SHOP =========================
    public function createCoffee()
    {
        $facilities = Facility::where('type', 'coffee_shop')
            ->with(['menuItems', 'gallery', 'services'])
            ->latest()
            ->get();

        return view('coffee', compact('facilities'));
    }

    public function storeCoffee(Request $request)
    {
        return $this->storeFacility($request, 'coffee_shop');
    }

    // ========================= GENERIC STORE FUNCTION =========================
    private function storeFacility(Request $request, $type)
    {
        Log::info("========== NEW {$type} REQUEST ==========");

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'manager' => 'nullable|string|max:255',
                'contact' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'website' => 'nullable|url|max:255',
                'capacity' => 'nullable|integer|min:1',
                'opening_hours' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
                'services' => 'nullable|array',
                'services.*' => 'nullable|string|max:255',
                'gallery' => 'nullable|array',
                'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
                'menu_items' => 'nullable|array',
                'menu_items.*.name' => 'required_with:menu_items|string|max:255',
                'menu_items.*.description' => 'nullable|string|max:500',
                'menu_items.*.category' => 'nullable|string|max:255',
                'menu_items.*.price' => 'nullable|numeric|min:0',
                'menu_items.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
            ]);

            DB::beginTransaction();

            $facility = new Facility();
            $facility->fill($validated);
            $facility->type = $type;

            // ✅ حفظ الصورة في public/images
            if ($request->hasFile('image')) {
                $folder = public_path('images/' . str_replace('_', '-', $type) . 's');
                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true);
                }

                $image = $request->file('image');
                $imageName = $type . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($folder, $imageName);
                $facility->image = 'images/' . str_replace('_', '-', $type) . 's/' . $imageName;

                Log::info("✅ Image uploaded: {$facility->image}");
            }

            $facility->save();

            // حفظ الخدمات
            if ($request->has('services') && is_array($request->services)) {
                foreach ($request->services as $serviceName) {
                    if (!empty(trim($serviceName))) {
                        Service::create([
                            'facility_id' => $facility->id,
                            'name' => trim($serviceName),
                        ]);
                    }
                }
            }

            // حفظ المعرض
            if ($request->hasFile('gallery')) {
                $galleryFolder = public_path('images/gallery');
                if (!file_exists($galleryFolder)) {
                    mkdir($galleryFolder, 0755, true);
                }

                foreach ($request->file('gallery') as $index => $galleryImage) {
                    $galleryName = 'gallery_' . time() . '_' . $index . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                    $galleryImage->move($galleryFolder, $galleryName);

                    Gallery::create([
                        'facility_id' => $facility->id,
                        'image' => 'images/gallery/' . $galleryName,
                    ]);
                }
            }

            // حفظ Menu Items
            if ($type === 'coffee_shop' && $request->has('menu_items') && is_array($request->menu_items)) {
                $this->saveMenuItems($facility->id, $request->menu_items, $request);
            }

            DB::commit();

            $routeName = $type === 'coffee_shop' ? 'dashboard.coffee' : 'dashboard.' . $type;

            return redirect()
                ->route($routeName)
                ->with('success', ucfirst(str_replace('_', ' ', $type)) . " added successfully! 🎉");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ Error creating {$type}: " . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    // ========================= دالة حفظ Menu Items =========================
    private function saveMenuItems($facilityId, $menuItemsData, $request)
    {
        $menuFolder = public_path('images/menu-items');
        if (!file_exists($menuFolder)) {
            mkdir($menuFolder, 0755, true);
        }

        foreach ($menuItemsData as $index => $menuData) {
            if (empty($menuData['name'])) {
                continue;
            }

            $menuItem = [
                'facility_id' => $facilityId,
                'name' => trim($menuData['name']),
                'description' => $menuData['description'] ?? null,
                'category' => $menuData['category'] ?? 'Coffee drinks',
                'price' => $menuData['price'] ?? 0,
            ];

            if ($request->hasFile("menu_items.{$index}.image")) {
                $menuImage = $request->file("menu_items.{$index}.image");
                $menuImageName = 'menu_' . time() . '_' . $index . '_' . uniqid() . '.' . $menuImage->getClientOriginalExtension();
                $menuImage->move($menuFolder, $menuImageName);
                $menuItem['image'] = 'images/menu-items/' . $menuImageName;
            }

            MenuItem::create($menuItem);
        }
    }

    // ========================= DELETE FUNCTIONS =========================
    public function deleteRestaurant($id)
    {
        try {
            $facility = Facility::where('type', 'restaurant')->findOrFail($id);

            DB::beginTransaction();

            // حذف الصور
            if ($facility->image && file_exists(public_path($facility->image))) {
                unlink(public_path($facility->image));
            }

            foreach ($facility->gallery as $gallery) {
                if ($gallery->image && file_exists(public_path($gallery->image))) {
                    unlink(public_path($gallery->image));
                }
                $gallery->delete();
            }

            foreach ($facility->menuItems as $menuItem) {
                if ($menuItem->image && file_exists(public_path($menuItem->image))) {
                    unlink(public_path($menuItem->image));
                }
                $menuItem->delete();
            }

            $facility->services()->delete();
            $facility->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.restaurant')
                ->with('success', 'تم حذف المطعم بنجاح ✅');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error deleting restaurant: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء الحذف');
        }
    }

    public function destroyCoffee($id)
    {
        try {
            $coffee = Facility::where('type', 'coffee_shop')->findOrFail($id);

            DB::beginTransaction();

            if ($coffee->image && file_exists(public_path($coffee->image))) {
                unlink(public_path($coffee->image));
            }

            foreach ($coffee->gallery as $gallery) {
                if ($gallery->image && file_exists(public_path($gallery->image))) {
                    unlink(public_path($gallery->image));
                }
                $gallery->delete();
            }

            foreach ($coffee->menuItems as $menuItem) {
                if ($menuItem->image && file_exists(public_path($menuItem->image))) {
                    unlink(public_path($menuItem->image));
                }
                $menuItem->delete();
            }

            $coffee->services()->delete();
            $coffee->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.coffee')
                ->with('success', 'تم حذف الكوفي شوب بنجاح ✅');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error deleting coffee shop: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء الحذف');
        }
    }

    public function destroy($id)
    {
        try {
            $facility = Facility::findOrFail($id);

            DB::beginTransaction();

            if ($facility->image && file_exists(public_path($facility->image))) {
                unlink(public_path($facility->image));
            }

            foreach ($facility->gallery as $gallery) {
                if ($gallery->image && file_exists(public_path($gallery->image))) {
                    unlink(public_path($gallery->image));
                }
                $gallery->delete();
            }

            foreach ($facility->menuItems as $menuItem) {
                if ($menuItem->image && file_exists(public_path($menuItem->image))) {
                    unlink(public_path($menuItem->image));
                }
                $menuItem->delete();
            }

            $facility->services()->delete();
            $facility->rooms()->delete();
            $facility->delete();

            DB::commit();

            return back()->with('success', 'تم الحذف بنجاح ✅');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error deleting facility: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء الحذف');
        }
    }

    // ========================= SERVICE & GALLERY & MENU MANAGEMENT =========================
    public function storeService(Request $request)
    {
        try {
            $validated = $request->validate([
                'facility_id' => 'required|exists:facilities,id',
                'name' => 'required|string|max:255',
            ]);

            Service::create($validated);
            return back()->with('success', 'تم إضافة الخدمة بنجاح ✅');

        } catch (\Exception $e) {
            Log::error('❌ Error adding service: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إضافة الخدمة');
        }
    }

    public function storeGallery(Request $request)
    {
        try {
            $validated = $request->validate([
                'facility_id' => 'required|exists:facilities,id',
                'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
            ]);

            if ($request->hasFile('image')) {
                $galleryFolder = public_path('images/gallery');
                if (!file_exists($galleryFolder)) {
                    mkdir($galleryFolder, 0755, true);
                }

                $image = $request->file('image');
                $imageName = 'gallery_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($galleryFolder, $imageName);

                Gallery::create([
                    'facility_id' => $validated['facility_id'],
                    'image' => 'images/gallery/' . $imageName,
                ]);

                return back()->with('success', 'تم إضافة الصورة بنجاح ✅');
            }

            return back()->with('error', 'لم يتم اختيار صورة');

        } catch (\Exception $e) {
            Log::error('❌ Error adding gallery image: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إضافة الصورة');
        }
    }

    public function storeMenu(Request $request)
    {
        try {
            $validated = $request->validate([
                'facility_id' => 'required|exists:facilities,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'category' => 'nullable|string|max:255',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
            ]);

            if ($request->hasFile('image')) {
                $menuFolder = public_path('images/menu-items');
                if (!file_exists($menuFolder)) {
                    mkdir($menuFolder, 0755, true);
                }

                $image = $request->file('image');
                $imageName = 'menu_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($menuFolder, $imageName);
                $validated['image'] = 'images/menu-items/' . $imageName;
            }

            MenuItem::create($validated);

            return back()->with('success', 'تم إضافة الصنف بنجاح ✅');

        } catch (\Exception $e) {
            Log::error('❌ Error adding menu item: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إضافة الصنف');
        }
    }
}
