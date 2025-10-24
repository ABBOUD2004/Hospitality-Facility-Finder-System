<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Room;
use App\Models\Service;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::withCount(['rooms', 'services', 'gallery'])->get();
        return view('dashboard.hotels.index', compact('facilities'));
    }

    public function create()
    {
        return view('dashboard.hotels.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'name' => 'required|string',
            'city' => 'required|string',
            'capacity' => 'required|integer',
            'manager' => 'nullable|string',
            'contact' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'services.*' => 'nullable|string',
            'gallery.*' => 'nullable|image|max:2048',
        ]);

        // Store main image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('facilities', 'public');
        }

        // Create facility
        $facility = Facility::create($data);

        // Store services
        if ($request->filled('services')) {
            foreach ($request->services as $serviceName) {
                if ($serviceName) {
                    $facility->services()->create(['name' => $serviceName]);
                }
            }
        }

        // Store gallery images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('galleries', 'public');
                $facility->gallery()->create(['path' => $path]);
            }
        }

        return redirect()->route('dashboard.hotel')->with('success', 'Hotel added successfully!');
    }

    public function edit(Facility $facility)
    {
        $facility->load(['rooms', 'services', 'gallery']);
        return view('dashboard.hotels.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'city' => 'required|string',
            'capacity' => 'required|integer',
            'manager' => 'nullable|string',
            'contact' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($facility->image) {
                Storage::disk('public')->delete($facility->image);
            }
            $data['image'] = $request->file('image')->store('facilities', 'public');
        }

        $facility->update($data);

        return back()->with('success', 'Hotel updated successfully!');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        return back()->with('success', 'Hotel deleted successfully!');
    }
}
