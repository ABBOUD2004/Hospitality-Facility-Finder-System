@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Edit Service</h1>

    <form method="POST" action="{{ route('service.update', $service->id) }}" class="bg-white shadow-md rounded p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Facility</label>
            <select name="facility_id" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                @foreach ($facilities as $facility)
                    <option value="{{ $facility->id }}" {{ $service->facility_id == $facility->id ? 'selected' : '' }}>
                        {{ $facility->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Name</label>
            <input type="text" name="name" value="{{ $service->name }}" class="w-full px-3 py-2 border border-gray-300 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Description</label>
            <textarea name="description" class="w-full px-3 py-2 border border-gray-300 rounded" rows="4">{{ $service->description }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Price</label>
            <input type="number" name="price" step="0.01" value="{{ $service->price }}" class="w-full px-3 py-2 border border-gray-300 rounded">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Update Service</button>
            <a href="{{ route('service.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
