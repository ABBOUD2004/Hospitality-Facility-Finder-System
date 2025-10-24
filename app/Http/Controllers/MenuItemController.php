<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Facility;

class MenuItemController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'facility_id' => 'required|integer|exists:facilities,id',
                'name' => 'required|string|max:255',
                'category' => 'required|string',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240' // 10MB
            ]);

            // حفظ الصورة بدون ضغط في مجلد public
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();

                // تحديد المجلد حسب نوع المنشأة
                $facility = Facility::find($validated['facility_id']);
                $folderType = $facility->type == 'coffee_shop' ? 'coffee-shop' : $facility->type;
                $folder = 'images/menu-items/' . $folderType;

                // إنشاء المجلد إذا لم يكن موجوداً
                if (!file_exists(public_path($folder))) {
                    mkdir(public_path($folder), 0755, true);
                }

                // نقل الملف
                $imageFile->move(public_path($folder), $imageName);
                $validated['image'] = $folder . '/' . $imageName;
            }

            // حفظ في قاعدة البيانات
            MenuItem::create($validated);

            return redirect()->back()->with('success', '✓ Menu item added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '✗ Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
            ]);

            $item = MenuItem::findOrFail($id);

            if ($request->hasFile('image')) {
                // حذف الصورة القديمة
                if ($item->image && file_exists(public_path($item->image))) {
                    unlink(public_path($item->image));
                }

                // حفظ الصورة الجديدة
                $imageFile = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();

                $facility = $item->facility;
                $folderType = $facility->type == 'coffee_shop' ? 'coffee-shop' : $facility->type;
                $folder = 'images/menu-items/' . $folderType;

                if (!file_exists(public_path($folder))) {
                    mkdir(public_path($folder), 0755, true);
                }

                $imageFile->move(public_path($folder), $imageName);
                $validated['image'] = $folder . '/' . $imageName;
            }

            $item->update($validated);

            return redirect()->back()->with('success', '✓ Menu item updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '✗ Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $item = MenuItem::findOrFail($id);

            // حذف الصورة
            if ($item->image && file_exists(public_path($item->image))) {
                unlink(public_path($item->image));
            }

            $item->delete();

            return redirect()->back()->with('success', '✓ Menu item deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '✗ Error: ' . $e->getMessage());
        }
    }
}
