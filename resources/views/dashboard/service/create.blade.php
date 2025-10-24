@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Add New Service</h1>

    <form method="POST" action="{{ route('service.store') }}" class="bg-white shadow-md rounded p-6">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Facility</label>
            <select name="facility_id" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                <option value="">Select Facility</option>
                @foreach ($facilities as $facility)
                    <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                @endforeach
            </select>
            @error('facility_id') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Name</label>
            <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded" required>
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Description</label>
            <textarea name="description" class="w-full px-3 py-2 border border-gray-300 rounded" rows="4"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Price</label>
            <input type="number" name="price" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Service</button>
            <a href="{{ route('service.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
