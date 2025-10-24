<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Service;
use App\Models\Gallery;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class CoffeeController extends Controller
{
    // ========================= عرض داشبورد الكوفي =========================
    public function index()
    {
        $facilities = Facility::where('type', 'coffee_shop')
            ->with(['services', 'gallery', 'menuItems'])
            ->latest()
            ->get();

        return view('coffee', compact('facilities'));
    }

    // ========================= حفظ مقهى جديد =========================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'capacity' => 'nullable|integer',
            'manager' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
            'services' => 'nullable|array',
            'menu_items' => 'nullable|array',
            'menu_items.*.name' => 'required_with:menu_items.*|string|max:255',
            'menu_items.*.description' => 'nullable|string', // ⭐ إضافة Validation
            'menu_items.*.category' => 'nullable|string|max:255',
            'menu_items.*.price' => 'nullable|numeric|min:0',
            'menu_items.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
        ]);

        // إنشاء المقهى
        $facility = new Facility();
        $facility->name = $request->name;
        $facility->city = $request->city;
        $facility->capacity = $request->capacity;
        $facility->manager = $request->manager;
        $facility->contact = $request->contact;
        $facility->description = $request->description;
        $facility->type = 'coffee_shop';

        // رفع الصورة الرئيسية
        if ($request->hasFile('image')) {
            $folder = 'images/coffee-shops';
            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0755, true);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($folder), $imageName);
            $facility->image = $folder . '/' . $imageName;
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

        // حفظ معرض الصور
        if ($request->hasFile('gallery')) {
            $galleryFolder = 'images/gallery';
            if (!file_exists(public_path($galleryFolder))) {
                mkdir(public_path($galleryFolder), 0755, true);
            }

            foreach ($request->file('gallery') as $galleryImage) {
                $galleryName = time() . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move(public_path($galleryFolder), $galleryName);

                Gallery::create([
                    'facility_id' => $facility->id,
                    'image' => $galleryFolder . '/' . $galleryName,
                ]);
            }
        }

        // ⭐ حفظ عناصر القائمة (Menu Items)
        if ($request->has('menu_items') && is_array($request->menu_items)) {
            $this->saveMenuItems($facility->id, $request->menu_items, $request);
        }

        return redirect()->route('dashboard.coffee')->with('success', 'Coffee Shop added successfully!');
    }

    // ========================= دالة مساعدة لحفظ Menu Items =========================
    private function saveMenuItems($facilityId, $menuItemsData, $request)
    {
        $menuFolder = 'images/menu-items';

        // إنشاء المجلد إذا لم يكن موجوداً
        $fullPath = public_path($menuFolder);
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        foreach ($menuItemsData as $index => $menuData) {
            // تحقق من أن الاسم موجود
            if (empty($menuData['name'])) {
                continue;
            }

            $menuItem = new MenuItem();
            $menuItem->facility_id = $facilityId;
            $menuItem->name = trim($menuData['name']);
            $menuItem->description = $menuData['description'] ?? null; // ⭐ إضافة Description
            $menuItem->category = $menuData['category'] ?? 'Coffee drinks';
            $menuItem->price = $menuData['price'] ?? 0;

            // رفع الصورة
            if ($request->hasFile("menu_items.{$index}.image")) {
                $menuImage = $request->file("menu_items.{$index}.image");
                $menuImageName = time() . '_' . uniqid() . '_' . $index . '.' . $menuImage->getClientOriginalExtension();

                // التأكد من رفع الصورة بنجاح
                if ($menuImage->move($fullPath, $menuImageName)) {
                    $menuItem->image = $menuFolder . '/' . $menuImageName;
                    \Log::info("Menu item image saved: " . $menuItem->image);
                } else {
                    \Log::error("Failed to upload menu item image for: " . $menuData['name']);
                }
            }

            $menuItem->save();
        }
    }

    // ========================= عرض صفحة تفاصيل المقهى (للزوار) =========================
    public function show($id)
    {
        $facility = Facility::with(['services', 'gallery', 'menuItems'])
            ->where('type', 'coffee_shop')
            ->findOrFail($id);

        return view('coffee-show', compact('facility'));
    }

    // ========================= عرض صفحة التعديل =========================
  public function edit($id)
{
    $facility = Facility::with(['services', 'gallery', 'menuItems'])->findOrFail($id);

    // If AJAX request, return JSON
    if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
            'id' => $facility->id,
            'name' => $facility->name,
            'city' => $facility->city,
            'capacity' => $facility->capacity,
            'manager' => $facility->manager,
            'contact' => $facility->contact,
            'description' => $facility->description,
            'image' => $facility->image ? asset('storage/' . $facility->image) : null,
        ]);
    }

    // Otherwise return view
    $facilities = Facility::where('type', 'coffee_shop')->get();
    return view('dashboard.coffee.edit', compact('facility', 'facilities'));
}

    // ========================= تحديث المقهى =========================
    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'capacity' => 'nullable|integer',
            'manager' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
            'menu_items' => 'nullable|array',
            'menu_items.*.name' => 'required_with:menu_items.*|string|max:255',
            'menu_items.*.description' => 'nullable|string', // ⭐ إضافة Validation
            'menu_items.*.category' => 'nullable|string|max:255',
            'menu_items.*.price' => 'nullable|numeric|min:0',
            'menu_items.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:51200',
        ]);

        $facility->name = $request->name;
        $facility->city = $request->city;
        $facility->capacity = $request->capacity;
        $facility->manager = $request->manager;
        $facility->contact = $request->contact;
        $facility->description = $request->description;

        // تحديث الصورة الرئيسية
        if ($request->hasFile('image')) {
            if ($facility->image && file_exists(public_path($facility->image))) {
                unlink(public_path($facility->image));
            }

            $folder = 'images/coffee-shops';
            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0755, true);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($folder), $imageName);
            $facility->image = $folder . '/' . $imageName;
        }

        $facility->save();

        // تحديث الخدمات (حذف القديمة وإضافة الجديدة)
        if ($request->has('services')) {
            Service::where('facility_id', $facility->id)->delete();
            foreach ($request->services as $serviceName) {
                if (!empty(trim($serviceName))) {
                    Service::create([
                        'facility_id' => $facility->id,
                        'name' => trim($serviceName),
                    ]);
                }
            }
        }

        // تحديث معرض الصور (إضافة جديدة فقط)
        if ($request->hasFile('gallery')) {
            $galleryFolder = 'images/gallery';
            if (!file_exists(public_path($galleryFolder))) {
                mkdir(public_path($galleryFolder), 0755, true);
            }

            foreach ($request->file('gallery') as $galleryImage) {
                $galleryName = time() . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move(public_path($galleryFolder), $galleryName);

                Gallery::create([
                    'facility_id' => $facility->id,
                    'image' => $galleryFolder . '/' . $galleryName,
                ]);
            }
        }

        // ⭐ تحديث عناصر القائمة (حذف القديمة وإضافة الجديدة)
        if ($request->has('menu_items') && is_array($request->menu_items)) {
            // حذف جميع Menu Items القديمة مع صورها
            $oldMenuItems = MenuItem::where('facility_id', $facility->id)->get();
            foreach ($oldMenuItems as $oldItem) {
                if ($oldItem->image && file_exists(public_path($oldItem->image))) {
                    unlink(public_path($oldItem->image));
                }
                $oldItem->delete();
            }

            // إضافة Menu Items الجديدة
            $this->saveMenuItems($facility->id, $request->menu_items, $request);
        }

        return redirect()->route('dashboard.coffee')->with('success', 'Coffee Shop updated successfully!');
    }

    // ========================= حذف المقهى =========================
    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);

        // حذف الصورة الرئيسية
        if ($facility->image && file_exists(public_path($facility->image))) {
            unlink(public_path($facility->image));
        }

        // حذف صور المعرض
        foreach ($facility->gallery as $gallery) {
            if ($gallery->image && file_exists(public_path($gallery->image))) {
                unlink(public_path($gallery->image));
            }
        }

        // حذف صور عناصر القائمة
        foreach ($facility->menuItems as $menuItem) {
            if ($menuItem->image && file_exists(public_path($menuItem->image))) {
                unlink(public_path($menuItem->image));
            }
        }

        $facility->delete();

        return redirect()->route('dashboard.coffee')->with('success', 'Coffee Shop deleted successfully!');
    }

    // ========================= حذف عنصر من القائمة =========================
    public function destroyMenuItem($id)
    {
        $menuItem = MenuItem::findOrFail($id);

        // حذف صورة العنصر
        if ($menuItem->image && file_exists(public_path($menuItem->image))) {
            unlink(public_path($menuItem->image));
        }

        $menuItem->delete();

        return back()->with('success', 'Menu item deleted successfully!');
    }

    // ========================= حذف صورة من المعرض =========================
    public function destroyGalleryImage($id)
    {
        $gallery = Gallery::findOrFail($id);

        // حذف الصورة
        if ($gallery->image && file_exists(public_path($gallery->image))) {
            unlink(public_path($gallery->image));
        }

        $gallery->delete();

        return back()->with('success', 'Gallery image deleted successfully!');
    }

}
