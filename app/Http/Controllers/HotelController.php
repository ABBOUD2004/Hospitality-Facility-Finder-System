<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Room;
use App\Models\Service;
use App\Models\Gallery;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    // ========================= عرض صفحة إدارة الفنادق =========================
public function index()
{
    $facilities = Facility::where('type', 'hotel')->orderBy('created_at', 'desc')->get();
    $rooms = Room::orderBy('created_at', 'desc')->get();

    return view('hotel', compact('facilities', 'rooms'));
}

    // ========================= حفظ الفندق الجديد =========================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200', // 50MB
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200',
            'rooms.*.name' => 'required|string|max:255',
            'rooms.*.price_usd' => 'required|numeric|min:0',
            'rooms.*.availability' => 'required|integer|min:0',
            'rooms.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200',
        ]);

        // إنشاء الفندق
        $facility = new Facility();
        $facility->name = $request->name;
        $facility->city = $request->city;
        $facility->address = $request->address;
        $facility->capacity = $request->capacity;
        $facility->manager = $request->manager;
        $facility->contact = $request->contact;
        $facility->email = $request->email;
        $facility->website = $request->website;
        $facility->description = $request->description;
        $facility->type = 'hotel'; // ⭐ توحيد النوع بحروف صغيرة

        // ========== رفع الصورة الرئيسية بدون ضغط ==========
        if ($request->hasFile('image')) {
            $hotelFolder = public_path('images/hotels');
            if (!file_exists($hotelFolder)) {
                mkdir($hotelFolder, 0755, true);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move($hotelFolder, $imageName);
            $facility->image = 'images/hotels/' . $imageName;
        }

        $facility->save();

        // ========== حفظ الخدمات ==========
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

        // ========== حفظ معرض الصور بدون ضغط ==========
        if ($request->hasFile('gallery')) {
            $galleryFolder = public_path('images/gallery');
            if (!file_exists($galleryFolder)) {
                mkdir($galleryFolder, 0755, true);
            }

            foreach ($request->file('gallery') as $galleryImage) {
                $galleryName = time() . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move($galleryFolder, $galleryName);

                Gallery::create([
                    'facility_id' => $facility->id,
                    'image' => 'images/gallery/' . $galleryName,
                ]);
            }
        }

        // ========== حفظ الغرف بدون ضغط ==========
        if ($request->has('rooms') && is_array($request->rooms)) {
            $roomFolder = public_path('images/rooms');
            if (!file_exists($roomFolder)) {
                mkdir($roomFolder, 0755, true);
            }

            foreach ($request->rooms as $index => $roomData) {
                $room = new Room();
                $room->facility_id = $facility->id;
                $room->name = $roomData['name'];
                $room->price_usd = $roomData['price_usd'];
                $room->price_rwf = $roomData['price_usd'] * 1300;
                $room->availability = $roomData['availability'];
                $room->description = $roomData['description'] ?? null;

                if (isset($roomData['image']) && $roomData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $roomImage = $roomData['image'];
                    $roomImageName = time() . '_' . uniqid() . '_' . $index . '.' . $roomImage->getClientOriginalExtension();
                    $roomImage->move($roomFolder, $roomImageName);
                    $room->image = 'images/rooms/' . $roomImageName;
                }

                $room->save();
            }
        }

        return redirect()->route('dashboard.hotel')->with('success', 'Hotel added successfully!');
    }

    // ========================= عرض صفحة التعديل =========================
    public function edit($id)
    {
        $facility = Facility::with('rooms', 'services', 'gallery')->findOrFail($id);
        $facilities = Facility::where('type', 'hotel')->get();
        return view('dashboard.hotel-edit', compact('facility', 'facilities'));
    }

    // ========================= تحديث الفندق =========================
    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200',
        ]);

        $facility->name = $request->name;
        $facility->city = $request->city;
        $facility->address = $request->address;
        $facility->capacity = $request->capacity;
        $facility->manager = $request->manager;
        $facility->contact = $request->contact;
        $facility->email = $request->email;
        $facility->website = $request->website;
        $facility->description = $request->description;

        // تحديث الصورة الرئيسية بدون ضغط
        if ($request->hasFile('image')) {
            if ($facility->image && file_exists(public_path($facility->image))) {
                unlink(public_path($facility->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $hotelFolder = public_path('images/hotels');
            if (!file_exists($hotelFolder)) {
                mkdir($hotelFolder, 0755, true);
            }

            $image->move($hotelFolder, $imageName);
            $facility->image = 'images/hotels/' . $imageName;
        }

        $facility->save();

        // تحديث الخدمات
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

        // تحديث معرض الصور بدون ضغط
        if ($request->hasFile('gallery')) {
            $galleryFolder = public_path('images/gallery');
            if (!file_exists($galleryFolder)) {
                mkdir($galleryFolder, 0755, true);
            }

            foreach ($request->file('gallery') as $galleryImage) {
                $galleryName = time() . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move($galleryFolder, $galleryName);

                Gallery::create([
                    'facility_id' => $facility->id,
                    'image' => 'images/gallery/' . $galleryName,
                ]);
            }
        }

        return redirect()->route('dashboard.hotel')->with('success', 'Hotel updated successfully!');
    }

    // ========================= حذف الفندق =========================
    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);

        if ($facility->image && file_exists(public_path($facility->image))) {
            unlink(public_path($facility->image));
        }

        foreach ($facility->rooms as $room) {
            if ($room->image && file_exists(public_path($room->image))) {
                unlink(public_path($room->image));
            }
        }

        foreach ($facility->gallery as $gallery) {
            if ($gallery->image && file_exists(public_path($gallery->image))) {
                unlink(public_path($gallery->image));
            }
        }

        $facility->delete();

        return redirect()->route('dashboard.hotel')->with('success', 'Hotel deleted successfully!');
    }
}
