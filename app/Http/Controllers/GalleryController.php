<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Facility;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'facility_id' => 'required|exists:facilities,id',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('gallery', 'public');
                $validated['image'] = $imagePath;
            }

            Gallery::create($validated);

            return redirect()->back()->with('success', 'Gallery image uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);

            // Delete image
            if ($gallery->image) {
                \Storage::disk('public')->delete($gallery->image);
            }

            $gallery->delete();

            return redirect()->back()->with('success', 'Gallery image deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getByFacility($facilityId)
    {
        $images = Gallery::where('facility_id', $facilityId)->get();
        return response()->json($images);
    }
}
